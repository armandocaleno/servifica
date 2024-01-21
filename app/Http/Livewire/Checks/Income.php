<?php

namespace App\Http\Livewire\Checks;

use App\Models\Accounting;
use App\Models\BankAccount;
use App\Models\Check;
use App\Models\Journal;
use App\Models\JournalDetail;
use Carbon\Carbon;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Income extends Component
{
    public $check, $account_id, $type;
    public $total_debe, $total_haber, $value;

    protected $rules = [
        'check.number' => 'required',
        'check.date' => 'required|date',
        'check.beneficiary' => 'required',
        'check.reference' => 'nullable|string|max:250',
        'check.total' => 'required|numeric|regex:/^[\d]{0,8}(\.[\d]{1,2})?$/',
        'check.bank_account_id' => 'required'
    ];

    public function mount($check)
    {
        $this->check = $check;

        Cart::instance('new_journal')->destroy();
        Cart::instance('old_journal')->destroy();

        if ($check->id == null) {
            $this->check->date = Carbon::now()->format('Y-m-d');
            $this->check->total = "0.00";
            $this->check->tax = "0.00";
            $this->total_debe = "0.00";
            $this->total_haber = "0.00";
            $this->value = "0.00";
        }else {
            $accountings = $this->check->journal->details;
            
            foreach ($accountings as $item ) {
                if ($item->credit_value > 0) {
                    $accounting = Accounting::find($item->accounting_id);
                    Cart::instance('old_journal')->add([
                        'id' => $item->id,
                        'name' =>$accounting->code . " " . $accounting->name,
                        'qty' => 1,
                        'price' => $item->credit_value,
                        'weight' => 100
                    ]); 
                    
                    Cart::instance('new_journal')->add([
                        'id' => $item->id,
                        'name' =>$accounting->code . " " . $accounting->name,
                        'qty' => 1,
                        'price' => $item->credit_value,
                        'weight' => 100
                    ]); 
                }
            }
        }
    }

    public function render()
    {
        $bankaccounts = BankAccount::where('company_id', session('company')->id)->get();
        $accountings = Accounting::where('company_id', session('company')->id)
                                    ->where('group', '0')
                                    ->orderBy('account_class_id')
                                    ->orderBy('code')
                                    ->get();

        return view('livewire.checks.income', compact('accountings', 'bankaccounts'));
    }

    public function save()
    {   
        $this->validate();

        if (floatVal(Cart::instance('new_journal')->subtotal(2,'.','')) == floatVal($this->check->total)) {

            DB::beginTransaction();

            try {

                if ($this->check->id != null) {
                    $this->check->save();

                    $this->check->journal->update([
                        'date' => $this->check->date,
                        'refence' => "Cheque: " .  $this->check->number . " - Beneficiario: " . $this->check->beneficiary,
                    ]);
                    
                    // ELIMINA LAS CUENTAS DEL HABER Y ACTUALIZA LAS DEL DEBE
                    foreach ($this->check->journal->details as $value) {
                        if ($value->credit_value > 0) {
                            JournalDetail::find($value->id)->delete();
                        }else {
                            if ($value->accounting_id == 1) {
                                $value->update([
                                    'debit_value' => $this->check->total,
                                ]);
                            }
                        }
                    }

                    // INGRESA LAS NUEVAS CUENTAS DEL HABER
                    foreach (Cart::instance('new_journal')->content() as $item) {
                        JournalDetail::create([
                            'journal_id' => $this->check->journal->id,
                            'accounting_id' => $item->id,
                            'debit_value' => $item->price,
                            'credit_value' => 0
                        ]);
                    }
                }else {

                    $bank_account = BankAccount::find($this->check->bank_account_id);
                    $bank_accounting_id = $bank_account->accounting_id;

                    $check = Check::create([
                        'number' => $this->check->number,
                        'date' => $this->check->date,
                        'beneficiary' => $this->check->beneficiary,
                        'reference' => $this->check->reference,
                        'total' => $this->check->total,
                        'bank_account_id' => $this->check->bank_account_id
                    ]);
                    
                   
                    // CREA ASIENTO CONTABLE
                    $journal = Journal::create([
                        'number' => $this->GenjournalNumber(),
                        'date' => $this->check->date,
                        'refence' => "Cheque: " .  $this->check->number . " - Beneficiario: " . $this->check->beneficiary,
                        'company_id' => session('company')->id,
                        'type' => Journal::AUTO,
                        'journable_id' => $check->id,
                        'journable_type' => check::class
                    ]);

                    JournalDetail::create([
                        'journal_id' => $journal->id,
                        'accounting_id' => $bank_accounting_id,
                        'debit_value' => $this->check->total,
                        'credit_value' => 0
                    ]);

                    foreach (Cart::instance('new_journal')->content() as $item) {
                        JournalDetail::create([
                            'journal_id' => $journal->id,
                            'accounting_id' => $item->id,
                            'debit_value' => 0,
                            'credit_value' => $item->price
                        ]);
                    }
                   
                }

                DB::commit();

                 // Muestra mensaje al usuario
                 $this->success('Guardado exitosamente.');

                 return redirect()->route('checks.index');

            } catch (\Throwable $th) {
                //throw $th;
                DB::rollback();
                $this->info('Hubo un error y no se guardó el cheque.');
                $this->info($th->getMessage());
            }
        } else {
            $this->info('Los totales del asiento no coinciden.'); 
            return;
        }
    }

    public function addAccount()
    {
        if ($this->value == 0) {
            $this->info('Monto incorrecto!');    
            return;
        }

        if ($this->account_id < 1) {
            $this->info('Seleccione una cuenta primero.');    
            return;
        }       

        $accounting = Accounting::find($this->account_id);

        Cart::instance('new_journal')->add([
            'id' => $accounting->id,
            'name' =>$accounting->code . " " . $accounting->name,
            'qty' => 1,
            'price' => $this->value,
            'weight' => 100,
            'options' => ['type' => $this->type]
        ]);                               
        
        $this->total_haber = $this->total_haber + $this->value;
        $this->check->total = number_format($this->total_haber, 2, '.', '');

        $this->value = "0.00";     
    }  

    public function unsetAccount($rowID)
    {
        Cart::instance('new_journal')->remove($rowID);
        $this->total_haber = Cart::instance('new_journal')->subtotal();

        $this->check->total = $this->total_haber;
        $this->check->total = number_format($this->total_haber, 2);
    }

     //Generar el número de transacción
     public function GenjournalNumber()
     {                
         $j = new Journal();
         $number = $j->getNumber();
         return $number;
     }     
 
     // Mensaje de confirmación de acción
     public function success($message)
     {
         $this->dispatchBrowserEvent('success', 
             [
                 'message' => $message,                       
             ]
         );       
     }
 
     // Mensaje de información
     public function info($message)
     {
         $this->dispatchBrowserEvent('info', 
             [
                 'message' => $message,                       
             ]
         );       
     }
}
