<?php

namespace App\Http\Livewire\Checks;

use App\Models\Accounting;
use App\Models\AccountingConfig;
use App\Models\BankAccount;
use App\Models\Check;
use App\Models\Expense;
use App\Models\Journal;
use App\Models\JournalDetail;
use App\Models\Supplier;
use Carbon\Carbon;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use PDF;

class Create extends Component
{
    public $check, $account_id, $type, $mov, $supplier, $supplier_id, $expenses, $lastid;
    public $total_debe, $total_haber, $value, $pay_value, $search, $suppliers;
    public $suppliersModal, $invoicesModal;
    public $confirmingVoucher;

    protected $rules = [
        'check.number' => 'required|integer',
        'check.date' => 'required|date',
        'check.beneficiary' => 'required',
        'check.reference' => 'nullable|string|max:250',
        'check.total' => 'required|numeric|regex:/^[\d]{0,8}(\.[\d]{1,2})?$/',
        'check.bank_account_id' => 'required'
    ];

    protected $listeners = ['changePayValue'];

    public function mount($check)
    {
        $this->check = $check;
        $this->lastid = 0;

        Cart::instance('new_journal')->destroy();
        Cart::instance('old_journal')->destroy();
        Cart::instance('expenses')->destroy();

        $this->confirmingVoucher = false;

        if ($check->id == null) {
            $this->check->date = Carbon::now()->format('Y-m-d');
            $this->check->total = "0.00";
            $this->check->tax = "0.00";
            $this->total_debe = "0.00";
            $this->total_haber = "0.00";
            $this->value = "0.00";
        } else {
            $accountings = $this->check->journal->details;

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

        $this->suppliersModal = false;
        $this->invoicesModal = false;
        $this->expenses = null;
        $this->pay_value = 0;
    }

    public function render()
    {
        $bankaccounts = BankAccount::where('company_id', session('company')->id)->where('type', '2')->get();

        return view('livewire.checks.create', compact('bankaccounts'));
    }

    public function save()
    {
        $this->validate();

        if (floatVal(Cart::instance('expenses')->subtotal(2, '.', '')) == floatVal($this->check->total)) {

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
                        } else {
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

                    //Obtiene el id del cheque ingresado
                    $this->lastid = $this->check->id;
                } else {

                    $bank_account = BankAccount::find($this->check->bank_account_id);
                    $bank_accounting_id = $bank_account->accounting_id;
                    $suppliers_account = AccountingConfig::where('name', 'suppliers')->first();

                    $check = Check::create([
                        'number' => $this->check->number,
                        'date' => $this->check->date,
                        'type' => "O",
                        'beneficiary' => $this->check->beneficiary,
                        'reference' => $this->check->reference,
                        'total' => $this->check->total,
                        'bank_account_id' => $this->check->bank_account_id
                    ]);

                    // INGRESO DE LAS FACTURAS ASOCIADAS
                    foreach (Cart::instance('expenses')->content() as $item) {
                        $check->expenses()->syncWithPivotValues([$item->id], ['value' => $item->price]);
                    }

                    // CREA ASIENTO CONTABLE
                    $journal = Journal::create([
                        'number' => $this->GenjournalNumber(),
                        'date' => $this->check->date,
                        'refence' => "Egreso - Cheque: " .  $this->check->number . " - Beneficiario: " . $this->check->beneficiary,
                        'company_id' => session('company')->id,
                        'type' => Journal::AUTO,
                        'journable_id' => $check->id,
                        'journable_type' => check::class
                    ]);

                    JournalDetail::create([
                        'journal_id' => $journal->id,
                        'accounting_id' => $bank_accounting_id,
                        'debit_value' => 0,
                        'credit_value' => $this->check->total
                    ]);

                    JournalDetail::create([
                        'journal_id' => $journal->id,
                        'accounting_id' => $suppliers_account->accounting_id,
                        'debit_value' => $this->check->total,
                        'credit_value' => 0
                    ]);

                    //Obtiene el id del cheque ingresado
                    $this->lastid = $check->id;
                }

                DB::commit();

                // Muestra mensaje al usuario
                $this->success('Guardado exitosamente.');

                $this->resetControls();

                //abre modal de descarga de recibo (voucher)
                $this->confirmingVoucher = true;

                // return redirect()->route('checks.index');
            } catch (\Throwable $th) {
                DB::rollback();
                $this->info('Hubo un error y no se guardó el cheque.');
                $this->info($th->getMessage());
            }
        } else {
            $this->info('Los totales del asiento no coinciden.');
            return;
        }
    }

    function resetControls() {
        $this->check = new Check();
        $this->supplier = null;
        Cart::instance('new_journal')->destroy();
        Cart::instance('old_journal')->destroy();
        Cart::instance('expenses')->destroy();
    }

    public function generatevoucher($id)
    {
        $this->confirmingVoucher = false;
        if ($id != null) {
            # code...
        
            $check = Check::find($id); //obtiene la transacción 
            // Arr::sort($check->voucher);
            if ($check != null) {
                # code...
            
                $check->date = Carbon::parse($check->date)->format('d/m/Y'); //formatea la fecha       
                // dd($check);
                //genera el pdf con el contenido de la transacción
                $pdf = PDF::loadView('checks.voucher', ['check' => $check, 'company' => session('company')]);

                //muestra el pdf    
                return $pdf->stream();
            }else {
                $this->info('Error al obtener los datos.');
            }
            
        }else {
            $this->info('Error al obtener los datos.');
        }
    }

    public function closeModal()
    {
        $this->confirmingVoucher = false;
    }

    public function unsetExpense($rowID)
    {
        Cart::instance('expenses')->remove($rowID);
        $this->check->total = Cart::instance('expenses')->subtotal(2);
    }

    public function openSuppliersModal()
    {
        $this->suppliers = Supplier::where('company_id', session('company')->id)->get();

        $this->suppliersModal = true;
    }

    public function updatedSearch()
    {
        $this->suppliers = Supplier::where('company_id', session('company')->id)
            ->where('name', 'LIKE', '%' . $this->search . '%')
            ->orWhere('identity', 'LIKE', $this->search . '%')->get();
    }

    public function openInvoicesModal()
    {
        if ($this->supplier_id != null) {
            $this->expenses = Expense::where('supplier_id', $this->supplier_id)->get();

            $this->invoicesModal = true;
        } else {
            $this->info('Seleccione primero un proveedor.');
        }
    }

    public function selectSupplier(Supplier $supplier)
    {
        $this->supplier_id = $supplier->id;
        $this->supplier = $supplier->name;
        $this->check->beneficiary = $supplier->name;
        $this->suppliersModal = false;
    }

    public function selectInvoice(Expense $expense)
    {
        Cart::instance('expenses')->add([
            'id' => $expense->id,
            'name' => $expense->number,
            'qty' => 1,
            'price' => $expense->residue(),
            'weight' => 100,
            'options' => ['date' => $expense->date, 'reference' => $expense->reference, 'total' => $expense->total]
        ]);

        $this->check->total = Cart::instance('expenses')->subtotal(2, '.', '');

        $this->invoicesModal = false;
    }

    public function changePayValue($content)
    {
        if (floatval($content['value']) > 0) {
            Cart::instance('expenses')->update($content['row'], [
                'price' => floatval($content['value'])
            ]);
        } else {
            $this->info('Valor incorrecto.');
            return;
        }

        $this->check->total = Cart::instance('expenses')->subtotal(2);
    }

    //Generar el número de asiento
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
