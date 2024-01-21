<?php

namespace App\Http\Livewire\Payments;

use Illuminate\Support\Facades\DB;
use Gloudemans\Shoppingcart\Facades\Cart;
use App\Models\Journal;
use App\Models\JournalDetail;
use App\Models\Accounting;
use App\Models\BankAccount;
use App\Models\Check;
use App\Models\Payment;
use App\Models\PaymentMethod;
use Carbon\Carbon;
use Livewire\Component;


class Create extends Component
{
    public $payment, $account_id, $lastid, $type;
    public $total_debe, $total_haber, $value;
    public $confirmingVoucher;

    protected $rules = [
        'payment.number' => 'required_if:payment.payment_method_id,2',
        'payment.date' => 'required|date',
        'payment.payment_method_id' => 'required',
        'payment.beneficiary' => 'nullable',
        // 'payment.emisor' => 'nullable',
        'payment.reference' => 'nullable|string|max:250',
        'payment.total' => 'required|numeric|regex:/^[\d]{0,8}(\.[\d]{1,2})?$/',
        'payment.bank_account_id' => 'required'
    ];

    public function mount($payment)
    {
        $this->payment = $payment;
        $this->lastid = 0;
        $this->confirmingVoucher = false;
        Cart::instance('new_journal')->destroy();
        Cart::instance('old_journal')->destroy();

        if ($payment->id == null) {
            $this->payment->date = Carbon::now()->format('Y-m-d');
            $this->payment->total = "0.00";
            $this->total_debe = "0.00";
            $this->total_haber = "0.00";
            $this->value = "0.00";
        } else {
            $accountings = $this->payment->journal->details;

            foreach ($accountings as $item) {
                if ($item->credit_value > 0) {
                    $accounting = Accounting::find($item->accounting_id);
                    Cart::instance('old_journal')->add([
                        'id' => $item->id,
                        'name' => $accounting->code . " " . $accounting->name,
                        'qty' => 1,
                        'price' => $item->credit_value,
                        'weight' => 100
                    ]);

                    Cart::instance('new_journal')->add([
                        'id' => $item->id,
                        'name' => $accounting->code . " " . $accounting->name,
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
        $paymentmethods = PaymentMethod::all();
        $accountings = Accounting::where('company_id', session('company')->id)
            ->where('group', '0')
            ->orderBy('account_class_id')
            ->orderBy('code')
            ->get();

        return view('livewire.payments.create', compact('bankaccounts', 'accountings', 'paymentmethods'));
    }

    public function save()
    {   
        $this->validate();
       
        if (floatVal(Cart::instance('new_journal')->subtotal(2,'.','')) == floatVal($this->payment->total)) {
            DB::beginTransaction();

            try {

                if ($this->payment->id != null) {
                    $this->payment->save();

                    $this->payment->journal->update([
                        'date' => $this->payment->date,
                        'refence' => "Pagos varios: Beneficiario: " . $this->payment->beneficiary,
                    ]);
                    
                    // ELIMINA LAS CUENTAS DEL HABER Y ACTUALIZA LAS DEL DEBE
                    foreach ($this->payment->journal->details as $value) {
                        if ($value->credit_value > 0) {
                            JournalDetail::find($value->id)->delete();
                        }else {
                            if ($value->accounting_id == 1) {
                                $value->update([
                                    'debit_value' => $this->payment->total,
                                ]);
                            }
                        }
                    }

                    // INGRESA LAS NUEVAS CUENTAS DEL HABER
                    foreach (Cart::instance('new_journal')->content() as $item) {
                        JournalDetail::create([
                            'journal_id' => $this->payment->journal->id,
                            'accounting_id' => $item->id,
                            'debit_value' => $item->price,
                            'credit_value' => 0
                        ]);
                    }
                }else {

                    $bank_account = BankAccount::find($this->payment->bank_account_id);
                    $bank_accounting_id = $bank_account->accounting_id;

                    $payment = Payment::create([
                        'number' => $this->payment->number,
                        'date' => $this->payment->date,
                        'beneficiary' => $this->payment->beneficiary,
                        // 'emisor' => $this->payment->emisor,
                        'payment_method_id' => $this->payment->payment_method_id,
                        'reference' => $this->payment->reference,
                        'total' => $this->payment->total,
                        'bank_account_id' => $this->payment->bank_account_id
                    ]);
                    
                   
                    // CREA ASIENTO CONTABLE
                    $journal = Journal::create([
                        'number' => $this->GenjournalNumber(),
                        'date' => $this->payment->date,
                        'refence' => "Pagos varios: Beneficiario: " . $this->payment->beneficiary,
                        'company_id' => session('company')->id,
                        'type' => Journal::AUTO,
                        'journable_id' => $payment->id,
                        'journable_type' => Payment::class
                    ]);

                    

                    foreach (Cart::instance('new_journal')->content() as $item) {
                        JournalDetail::create([
                            'journal_id' => $journal->id,
                            'accounting_id' => $item->id,
                            'debit_value' => $item->price,
                            'credit_value' => 0
                        ]);
                    }

                    JournalDetail::create([
                        'journal_id' => $journal->id,
                        'accounting_id' => $bank_accounting_id,
                        'debit_value' => 0,
                        'credit_value' => $this->payment->total
                    ]);

                    //Pago en cheque
                    $payment_method = PaymentMethod::find($this->payment->payment_method_id);

                    if ($payment_method->name != "Efectivo") {
                        Check::create([
                            'number' => $this->payment->number,
                            'date' => $this->payment->date,
                            'type' => "O",
                            'beneficiary' => $this->payment->beneficiary,
                            'reference' => $this->payment->reference,
                            'total' => $this->payment->total,
                            'bank_account_id' => $this->payment->bank_account_id
                        ]);
                    }
                   
                }

                DB::commit();

                //Obtiene el id del cheque ingresado
                $this->lastid = $payment->id;

                 // Muestra mensaje al usuario
                 $this->success('Guardado exitosamente.');

                 //abre modal de descarga de recibo (voucher)
                $this->confirmingVoucher = true;

            } catch (\Throwable $th) {
                //throw $th;
                DB::rollback();
                $this->info('Hubo un error y no se guardó el pago.');
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
            'name' => $accounting->code . " " . $accounting->name,
            'qty' => 1,
            'price' => $this->value,
            'weight' => 100,
            'options' => ['type' => $this->type]
        ]);

        $this->total_haber = $this->total_haber + $this->value;
        $this->payment->total = number_format($this->total_haber, 2, '.', '');

        $this->value = "0.00";
    }

    public function unsetAccount($rowID)
    {
        Cart::instance('new_journal')->remove($rowID);
        $this->total_haber = Cart::instance('new_journal')->subtotal();

        $this->payment->total = $this->total_haber;
        $this->payment->total = number_format($this->total_haber, 2);
    }

    public function closeModal()
    {
        $this->confirmingVoucher = false;
        return redirect()->route('payments.index');
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
        $this->dispatchBrowserEvent(
            'success',
            [
                'message' => $message,
            ]
        );
    }

    // Mensaje de información
    public function info($message)
    {
        $this->dispatchBrowserEvent(
            'info',
            [
                'message' => $message,
            ]
        );
    }
}
