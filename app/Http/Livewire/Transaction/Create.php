<?php

namespace App\Http\Livewire\Transaction;

use App\Jobs\VoucherMailJob;
use App\Models\Account;
use App\Models\AccountingConfig;
use App\Models\Journal;
use App\Models\JournalDetail;
use App\Models\Partner;
use App\Models\Transaction;
use Carbon\Carbon;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use PDF;
use App\Mail\VoucherMail;
use App\Models\Company;
use Illuminate\Support\Facades\Mail;

class Create extends Component
{
    public $transaction, $amount, $account_id, $saldo, $lastid, $account, $acc;
    public $confirmingVoucher;
    public $old_partner, $old_type;

    protected $rules = [
        'transaction.date' => 'required|date',
        'transaction.number' => 'required|max:15',
        'transaction.reference' => 'max:255',
        'transaction.total' => 'required|numeric|regex:/^[\d]{0,6}(\.[\d]{1,2})?$/',
        'transaction.partner_id' => 'required',
        'transaction.content' => 'required'
    ];

    public function mount()
    {
        $this->account_id = 0;
        $this->amount = "0.00";
        $this->saldo = "0.00";

        $this->lastid = 0;
        $this->old_partner = "";
        $this->old_type = "";

        Cart::instance('old')->destroy();
        Cart::instance('new')->destroy();
        Cart::instance('voucher')->destroy();

        $this->confirmingVoucher = false;

        if ($this->transaction->id) {
            $this->transaction->date = Carbon::parse($this->transaction->date)->format('Y-m-d');
            $this->old_partner = $this->transaction->partner_id;
            $this->old_type = $this->transaction->type;

            foreach ($this->transaction->content as $value) {
                Cart::instance('old')->add([
                    'id' => $value['id'],
                    'name' => $value['name'],
                    'qty' => 1,
                    'price' => $value['price'],
                    'weight' => 550,
                    'options' => ['previus' => $value['options']['previus'], 'new' => $value['options']['new']]
                ]);

                Cart::instance('new')->add([
                    'id' => $value['id'],
                    'name' => $value['name'],
                    'qty' => 1,
                    'price' => $value['price'],
                    'weight' => 550,
                    'options' => ['previus' => $value['options']['previus'], 'new' => $value['options']['new']]
                ]);
            }
        } else {
            $this->transaction = new Transaction();
            $this->transaction->date = Carbon::now()->format('Y-m-d');
            $this->transaction->total = Cart::instance('new')->subtotal();
            $this->transaction->number = $this->GenTransactionNumber();
            $this->transaction->type = Transaction::INDIVIDUAL;
        }
    }

    public function render()
    {
        $partners = Partner::where('company_id', session('company')->id)->where('status', Partner::ACTIVO)->get();

        $accounts = Account::all();

        return view('livewire.transaction.create', compact('partners', 'accounts'));
    }

    public function updatedAccountId($value)
    {
        $account = "";
        if ($this->transaction->partner_id) {
            $account = Partner::find($this->transaction->partner_id)->accounts()->where('account_id', $value)->get();

            if ($account->count()) {
                $this->saldo = $account[0]->pivot->value;
            } else {
                $this->saldo = "0.00";
            }
        }
    }

    public function updatedTransactionPartnerId($value)
    {
        $account = "";
        if ($value) {
            $account = Partner::find($value)->accounts()->where('account_id', $this->account_id)->get();

            if ($account->count()) {
                $this->saldo = $account[0]->pivot->value;
            } else {
                $this->saldo = "0.00";
            }
        }
    }

    public function addAccount()
    {
        $newtotal = "";

        if ($this->amount < 1) {
            $this->info('Monto incorrecto!');
            return;
        }

        if ($this->account_id < 1) {
            $this->info('Seleccione una cuenta primero.');
            return;
        }

        $account = Account::find($this->account_id);

        //Defino si es ingreso o egreso y se cambia el signo para que reste si es el caso.
        if ($account->type == Account::INGRESO) {
            $newtotal = $this->saldo - $this->amount;
        } else {
            $newtotal = $this->saldo + $this->amount;
        }

        Cart::instance('new')->add([
            'id' => $account->id,
            'name' => $account->name,
            'qty' => 1,
            'price' => $this->amount,
            'weight' => 100,
            'options' => ['previus' => $this->saldo, 'new' => $newtotal, 'accounting_id' => $account->accounting_id]
        ]);

        $this->amount = "0.00";
        $this->transaction->total = Cart::instance('new')->subtotal();
    }

    public function unsetAccount($rowID)
    {
        Cart::instance('new')->remove($rowID);
        $this->transaction->total = Cart::instance('new')->subtotal();
    }

    public function save()
    {
        
        $this->transaction->content = Cart::instance('new')->content();
        $this->transaction->total = Cart::instance('new')->subtotal(2, ".", "");


        $accounts = Account::all(); //obtiene todas las cuentas
        $partner = Partner::find($this->transaction->partner_id);
        

        if ($this->transaction->id) {
            //actualiza los saldos con el detalle antiguo
            $this->totalRoolback();

            //actualiza la transacción con los nuevos datos
            $this->transaction->save();

            //almacena el id de la transacción guardada
            $this->lastid = $this->transaction->id;
        } else {
            $this->validate();

            //alamacena todas las cuentas con los saldos en el carrito de vaucher            
            foreach ($accounts as $account) {
                $previus = 0;
                $new = 0;

                if ($partner->accounts()->find($account->id)) {
                    $previus = $partner->accounts()->find($account->id)->pivot->value;
                    $new = $partner->accounts()->find($account->id)->pivot->value;
                }

                //añade el contenido de la cuenta en el carrito
                Cart::instance('voucher')->add([
                    'id' => $account->id,
                    'name' => $account->name,
                    'qty' => 1,
                    'price' => 0,
                    'weight' => 100,
                    'options' => ['previus' => $previus, 'new' => $new]
                ]);
            }

            //actualiza las cuentas del voucher que están en la trasacción actual
            foreach (Cart::instance('voucher')->content() as $voucher) {
                foreach ($this->transaction->content as $value) {
                    if ($voucher->id === $value['id']) {
                        Cart::instance('voucher')->update($voucher->rowId, [
                            'price' => $value['price'],
                            'options' => ['previus' => $value['options']['previus'], 'new' => $value['options']['new']]
                        ]);
                    }
                }
            }

            //guarda los rubros de todas las cuentas en el objeto
            $this->transaction->voucher = Cart::instance('voucher')->content();

            DB::beginTransaction();

            try {
                //guarda la transacción
                $transaction = Transaction::create([
                    'date' => $this->transaction->date,
                    'number' => $this->transaction->number,
                    'partner_id' => $this->transaction->partner_id,
                    'reference' => $this->transaction->reference,
                    'total' => $this->transaction->total,
                    'content' => $this->transaction->content,
                    'voucher' => $this->transaction->voucher,
                    'type' => Transaction::INDIVIDUAL,
                    'company_id' => session('company')->id
                ]);

                // CREA ASIENTO CONTABLE

                // OBTENER NUMERO DE ASIENTO
                $j = new Journal();
                $number = $j->getNumber();

                // CUENTA DE SOCIOS
                $account_cash = AccountingConfig::where('name', 'cash')->first()->accounting_id;

                $journal = Journal::create([
                    'number' => $number,
                    'date' => $this->transaction->date,
                    'refence' => "Transacción: " .  $this->transaction->number . " - Socio: " . $partner->name,
                    'company_id' => session('company')->id,
                    'type' => Journal::AUTO,
                    'journable_id' => $transaction->id,
                    'journable_type' => Transaction::class
                ]);

                JournalDetail::create([
                    'journal_id' => $journal->id,
                    'accounting_id' => $account_cash,
                    'debit_value' => $this->transaction->total,
                    'credit_value' => 0
                ]);

                foreach (Cart::instance('new')->content() as $item) {

                    $accounting_id = $item->options['accounting_id'];

                    JournalDetail::create([
                        'journal_id' => $journal->id,
                        'accounting_id' => $accounting_id,
                        'debit_value' => 0,
                        'credit_value' => $item->price
                    ]);
                }

                //Obtiene el id de la transacción ingresada
                $this->lastid = $transaction->id;

                //Registra o actualiza saldos en cuentas
                $this->totalUpdate($this->transaction);

                DB::commit();

                // Muestra mensaje al usuario
                $this->success('Cobro guardado correctamente.');

                $this->resetControls();
   
                $this->generateVoucherEmail($this->lastid);

                //abre modal de descarga de recibo (voucher)
                $this->confirmingVoucher = true;
                
            } catch (\Throwable $th) {
                //throw $th;
                DB::rollback();
                $this->info('Hubo un error y no se guardó el cobro.');
                $this->info($th->getMessage());
            }
        }
    }

    public function resetControls()
    {
        Cart::instance('old')->destroy();
        Cart::instance('new')->destroy();
        Cart::instance('voucher')->destroy();

        $this->reset('transaction');

        $this->transaction = new Transaction();
        $this->transaction->date = Carbon::now()->format('Y-m-d');
        $this->transaction->total = Cart::instance('new')->subtotal();
        $this->transaction->number = $this->GenTransactionNumber();
        $this->transaction->type = Transaction::INDIVIDUAL;

        $this->amount = "0.00";
        $this->saldo = "0.00";
    }

    public function closeModal()
    {
        $this->confirmingVoucher = false;
    }

    public function generatevoucher($id)
    {
        $this->confirmingVoucher = false;
        // Cart::destroy(); //borrar carrito
        $transaction = Transaction::find($id); //obtiene la transacción 
       
        Arr::sort($transaction->voucher);
        $transaction->date = Carbon::parse($transaction->date)->format('d/m/Y'); //formatea la fecha       

        //genera el pdf con el contenido de la transacción
        $pdf = PDF::loadView('transactions.voucher', ['transaction' => $transaction]);
        //muestra el pdf    
        return $pdf->stream();
    }

    public function generateVoucherEmail($id)
    {
        $transaction = Transaction::find($id); //obtiene la transacción 
    
        Arr::sort($transaction->voucher);
        $transaction->date = Carbon::parse($transaction->date)->format('d/m/Y'); //formatea la fecha       
        $voucher_path = '/app/Pago_'. $transaction->number . '.pdf';
        //genera el pdf con el contenido de la transacción
        
        // $pdf = PDF::loadView('transactions.voucher', ['transaction' => $transaction]);
        $pdf = PDF::loadView('transactions.voucher', ['transaction' => $transaction])->save(storage_path(). $voucher_path);
        //muestra el pdf    
        // return $pdf->stream();
        // return PDF::loadFile(public_path().'/myfile.html')->save('/path-to/my_stored_file.pdf')->stream('download.pdf');
        // return $pdf->download('voucher.pdf');

        $this->emailSend($transaction, $voucher_path);
    }

    public function totalUpdate(Transaction $transaction)
    {
        //setea el contenido de la transacción
        $content = $transaction->content;

        //Obtiene el socio de la transacción
        $partner = Partner::find($transaction->partner_id);

        foreach ($content as $value) {
            //setea el saldo de la cuenta
            $newtotal = $value['options']['new'];

            //Consulta si el socio ya tiene la cuenta ingresada.
            $account_partner = $partner->accounts()->find($value['id']);

            //si ya existe sumo el valor actual con el existente y actualiza la tabla relacionada.
            if ($account_partner) {
                $partner->accounts()->updateExistingPivot($account_partner->id, ['value' => $newtotal]);
            } else {
                //si no existe inserta la cuenta con el valor respectivo.                
                $partner->accounts()->attach($value['id'], ['value' => $newtotal]);
            }
        }
    }

    public function totalRoolback()
    {
        //setea el contenido de la transacción
        $content = Cart::instance('old')->content();

        //Obtiene el socio de la transacción
        $partner = Partner::find($this->old_partner);

        foreach ($content as $value) {
            //setea el saldo de la cuenta
            $total = $value->price;

            //Consulta si el socio ya tiene la cuenta ingresada.
            $account_partner = $partner->accounts()->find($value->id);

            //consulta la cuenta asociada
            $account = Account::find($value->id);
            //setea el valor de cuenta existente
            $amount = $account_partner->pivot->value;

            $newtotal = $amount + $total;
            if ($this->old_type == Transaction::LOTES || $account->type == Account::EGRESO) {
                $newtotal = $amount - $total;
            }

            //si ya existe actualiza la tabla relacionada con el nuevo saldo.
            if ($account_partner) {
                $partner->accounts()->updateExistingPivot($account_partner->id, ['value' => $newtotal]);
            }
        }
    }

    //Generar el número de transacción
    public function GenTransactionNumber()
    {
        $nuevoNumero = 1000001;

        $lastRegister = Transaction::whereIn('type', [Transaction::INDIVIDUAL, Transaction::LOTES])->latest('id')->first();

        if ($lastRegister) {
            $number = intval($lastRegister->number);
            
            $nuevoNumero = $number + 1;
        }

        return $nuevoNumero;
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
 
    function emailSend(Transaction $transaction, $path) {
        $company = session('company');
        if ($transaction->partner->email === null || $transaction->partner->email === '') {
            $this->info('No es posible enviar email. Email no válido.');
            return;
        } else {
            if (Mail::to($transaction->partner->email)->send(new VoucherMail($transaction, $company))) {
                $this->success('Email enviado!');
            } else {
                $this->info('No se pudo enviar el email.');
            }
            // VoucherMailJob::dispatch($transaction, $company);
        }
        
        // eliminar archivo generado
        if (file_exists(storage_path().$path)) {
            unlink(storage_path().$path);
        }
    }
}
