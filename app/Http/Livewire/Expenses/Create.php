<?php

namespace App\Http\Livewire\Expenses;

use App\Models\Accounting;
use App\Models\AccountingConfig;
use App\Models\Expense;
use App\Models\Journal;
use App\Models\JournalDetail;
use App\Models\Supplier;
use Carbon\Carbon;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Create extends Component
{
    public $expense, $account_id, $type, $number;
    public $total_debe, $total_haber, $value, $iva;

    protected $rules = [
        'expense.number' => 'required',
        'expense.date' => 'required|date',
        'expense.supplier_id' => 'required',
        'expense.reference' => 'nullable|string|max:250',
        'expense.total' => 'required|numeric|regex:/^[\d]{0,8}(\.[\d]{1,2})?$/',
        'expense.tax' => 'required|numeric|min:0|regex:/^[\d]{0,8}(\.[\d]{1,2})?$/',
        'expense.auth_number' => 'nullable|numeric',
    ];

    public function mount($expense)
    {
        $this->expense = $expense;

        Cart::instance('new_journal')->destroy();
        Cart::instance('old_journal')->destroy();

        if ($expense->id == null) {
            $this->expense->date = Carbon::now()->format('Y-m-d');
            $this->expense->total = "0.00";
            $this->expense->tax = "0.00";
            $this->total_debe = "0.00";
            $this->total_haber = "0.00";
            $this->value = "0.00";
        }else {
            $accountings = $this->expense->journal->details;
            
            foreach ($accountings as $item ) {
                if ($item->debit_value > 0) {
                    $accounting = Accounting::find($item->accounting_id);
                    Cart::instance('old_journal')->add([
                        'id' => $item->id,
                        'name' =>$accounting->code . " " . $accounting->name,
                        'qty' => 1,
                        'price' => $item->debit_value,
                        'weight' => 100
                    ]); 
                    
                    Cart::instance('new_journal')->add([
                        'id' => $item->id,
                        'name' =>$accounting->code . " " . $accounting->name,
                        'qty' => 1,
                        'price' => $item->debit_value,
                        'weight' => 100
                    ]); 
                }
            }
        }

        //  dd($this->expense);

    }

    public function render()
    {
        $accountings = Accounting::where('company_id', session('company')->id)
                                    ->where('group', '0')
                                    ->orderBy('code')->get();
                                    
        $suppliers = Supplier::where('company_id', session('company')->id)->get();

        return view('livewire.expenses.create', compact('accountings', 'suppliers'));
    }

    public function addAccount()
    {
        if ($this->value < 1) {
            $this->info('Monto incorrecto!');    
            return;
        }

        if ($this->account_id < 1) {
            $this->info('Seleccione una cuenta primero.');    
            return;
        }       

        $accounting = Accounting::find($this->account_id);

        $newcart = Cart::instance('new_journal')->add([
            'id' => $accounting->id,
            'name' =>$accounting->code . " - " . $accounting->name,
            'qty' => 1,
            'price' => $this->value,
            'weight' => 100,
            'options' => ['type' => $this->type]
        ]);                   
        
        if ($this->iva) {
            Cart::instance('new_journal')->setTax($newcart->rowId,12);
            
        }else {
            Cart::instance('new_journal')->setTax($newcart->rowId,0);
        }
        $this->expense->tax = Cart::instance('new_journal')->tax(2, '.', '');
        $this->total_haber = $this->total_haber + $this->value;
        $this->expense->total = Cart::instance('new_journal')->total(2, '.', '');

        $this->value = "0.00";  
        $this->iva = false;   
    }  

    public function unsetAccount($rowID)
    {
        Cart::instance('new_journal')->remove($rowID);
        $this->total_haber = Cart::instance('new_journal')->subtotal(2, '.', '');
        $this->expense->tax = Cart::instance('new_journal')->tax(2, '.', '');
        $this->expense->total = Cart::instance('new_journal')->total(2, '.', '');
    }

    public function save()
    {   
        $this->validate();
    
        $account_expenses_tax = AccountingConfig::where('name', 'iva_compras')->first()->accounting_id;
        $account_expenses = AccountingConfig::where('name', 'gastos')->first()->accounting_id;

        if ($account_expenses_tax == null || $account_expenses == null) {
            $this->info('No se han configurado las cuentas de gastos.'); 
            return;
        }
        
        DB::beginTransaction();

        try {

            if ($this->expense->id != null) {
                $this->expense->save();

                $this->expense->journal->update([
                    'date' => $this->expense->date,
                    'refence' => "Compra: " .  $this->expense->number . " - Proveedor: " . $this->expense->suppliers->name,
                ]);

                // ELIMINA LAS CUENTAS DEL HABER Y ACTUALIZA LAS DEL DEBE
                foreach ($this->expense->journal->details as $value) {
                    if ($value->credit_value > 0) {
                        JournalDetail::find($value->id)->delete();
                    }else {
                        if ($value->accounting_id == 1) {
                            $value->update([
                                'debit_value' => $this->expense->total,
                            ]);
                        }else {
                            $value->update([
                                'debit_value' => $this->expense->tax,
                            ]);
                        }
                    }
                }

                // INGRESA LAS NUEVAS CUENTAS DEL HABER
                foreach (Cart::instance('new_journal')->content() as $item) {
                    JournalDetail::create([
                        'journal_id' => $this->expense->journal->id,
                        'accounting_id' => $item->id,
                        'debit_value' => 0,
                        'credit_value' => $item->price
                    ]);
                }
            }else {

                $invoice = Expense::where('supplier_id', $this->expense->suppliers->id)
                                    ->where('number', $this->expense->number)->get();

                
                if (count($invoice)) {
                    $this->info('Ya existe una factura con este número de este proveedor.'); 
                    return;
                }

                $expense = Expense::create([
                    'number' => $this->expense->number,
                    'date' => $this->expense->date,
                    'supplier' => $this->expense->suppliers->name,
                    'supplier_id' => $this->expense->suppliers->id,
                    'reference' => $this->expense->reference,
                    'tax' => $this->expense->tax,
                    'total' => $this->expense->total,
                    'auth_number' => $this->expense->auth_number,
                    'company_id' => session('company')->id
                ]);
                
                // CREA ASIENTO CONTABLE
                $journal = Journal::create([
                    'number' => $this->GenjournalNumber(),
                    'date' => $this->expense->date,
                    'refence' => "Compra: " .  $this->expense->number . " - Proveedor: " . $this->expense->suppliers->name,
                    'company_id' => session('company')->id,
                    'type' => Journal::AUTO,
                    'journable_id' => $expense->id,
                    'journable_type' => Expense::class
                ]);

                JournalDetail::create([
                    'journal_id' => $journal->id,
                    'accounting_id' => $account_expenses,
                    'debit_value' => 0,
                    'credit_value' => $this->expense->total
                ]);

                if ($this->expense->tax > 0) {
                    JournalDetail::create([
                        'journal_id' => $journal->id,
                        'accounting_id' => $account_expenses_tax,
                        'debit_value' => $this->expense->tax,
                        'credit_value' => 0
                    ]);
                }

                foreach (Cart::instance('new_journal')->content() as $item) {
                    JournalDetail::create([
                        'journal_id' => $journal->id,
                        'accounting_id' => $item->id,
                        'debit_value' => $item->price,
                        'credit_value' => 0
                    ]);
                }
            }

            DB::commit(); // confirma la transacción

            // Muestra mensaje al usuario
            $this->success('Guardado exitosamente.');

            return redirect()->route('expenses.index');

        } catch (\Throwable $th) {
            DB::rollback();
            $this->info('Hubo un error y no se guardó la compra.');
            $this->info($th->getMessage());
        }
       
    }

    public function resetControls()
    {
        Cart::instance('old_journal')->destroy();
        Cart::instance('new_journal')->destroy();

        $this->reset('expense');

        $this->expense = new Expense();
        $this->expense->date = Carbon::now()->format('Y-m-d');
        $this->expense->total = "0.00";

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
