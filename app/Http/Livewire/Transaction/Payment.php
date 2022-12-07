<?php

namespace App\Http\Livewire\Transaction;

use App\Models\Account;
use App\Models\BankAccount;
use App\Models\Partner;
use App\Models\Transaction;
use PDF;
use Carbon\Carbon;
use Gloudemans\Shoppingcart\Facades\Cart;
use Livewire\Component;

class Payment extends Component
{
    public $transaction, $amount, $account_id, $saldo, $lastid, $account, $acc;
    public $confirmingVoucher;
    public $old_partner, $old_type;
    public $bank_account_id, $check_number;

    protected $rules = [
        'transaction.date' => 'required|date',
        'transaction.number' => 'required|max:15',
        'transaction.reference' => 'max:255',
        'transaction.total' => 'required|numeric|regex:/^[\d]{0,6}(\.[\d]{1,2})?$/',
        'transaction.partner_id' => 'required',
        'transaction.content' => 'required',
        'bank_account_id' => 'required',
        'check_number' => 'required'
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
            $this->transaction->type = Transaction::PAGO;          
        }        
    }

    public function render()
    {
        $partners = Partner::where('company_id', session('company')->id)->where('status', Partner::ACTIVO)->get();

        $accounts = Account::whereType(Account::EGRESO)->get();

        $bank_accounts = BankAccount::all();

        return view('livewire.transaction.payment', compact('partners', 'accounts', 'bank_accounts'));
    }

    public function updatedAccountId($value)
    {               
        $account = "";
        if ($this->transaction->partner_id) {
            $account = Partner::find($this->transaction->partner_id)->accounts()->where('account_id', $value)->get();            

            if ($account->count()) {                
                $this->saldo = $account[0]->pivot->value;
            }else {
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
            }else {
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

        $newtotal = $this->saldo - $this->amount;

        if ($this->saldo < $this->amount) {
            $this->info('No hay fondos suficientes en esta cuenta.');    
            return;
        }

        Cart::instance('new')->add([
            'id' => $account->id,
            'name' => $account->name,
            'qty' => 1,
            'price' => $this->amount,
            'weight' => 100,
            'options' => ['previus' => $this->saldo, 'new' => $newtotal]        
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
        $this->transaction->total = Cart::instance('new')->subtotal(2,".", "");
        

        $accounts = Account::all(); //obtiene todas las cuentas
        $partner = Partner::find($this->transaction->partner_id);

        if ($this->transaction->id) {
            //actualiza los saldos con el detalle antiguo
            $this->totalRoolback();

            //actualiza la transacción con los nuevos datos
            $this->transaction->save();

            //alamacena el id de la transacción guardada
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
                foreach ($this->transaction->content as $value ) {  
                    if ($voucher->id === $value['id']) {
                        Cart::instance('voucher')->update($voucher->rowId,[                                                                                    
                            'price' => $value['price'],                            
                            'options' => ['previus' => $value['options']['previus'], 'new' => $value['options']['new']]        
                        ]);  
                    }              
                     
                }
            }                         
            
            //guarda los rubros de todas las cuentas en el objeto transacción
            $this->transaction->voucher = Cart::instance('voucher')->content();

            //obtiene la cuenta bancaria asociada
            $bank_account = BankAccount::find($this->bank_account_id);

            //crea el array con la información adicional y la asigna al objeto transacción
            $aditional_info = ['bank_account_id' => $this->bank_account_id, 'bank'=> $bank_account->bank->name,'check_number' => $this->check_number];           
            $this->transaction->aditional_info = $aditional_info;
            
            //guarda la transacción
            $transaction = Transaction::create([
                'date' => $this->transaction->date,
                'number' => $this->transaction->number,
                'partner_id' => $this->transaction->partner_id,
                'reference' => $this->transaction->reference,
                'total' => $this->transaction->total,                
                'content' => $this->transaction->content,
                'voucher' => $this->transaction->voucher,
                'aditional_info' => $this->transaction->aditional_info,
                'type' => Transaction::PAGO,
                'company_id' => session('company')->id
            ]);                        
            
            //Obtiene el id de la transacción ingresada
            $this->lastid = $transaction->id;                        
        }

        //Registra o actualiza saldos en cuentas
        $this->totalUpdate($this->transaction);

        // Muesttra mensaje al usuario
        $this->success('Guardado exitosamente.');                               
       
        $this->resetControls();        

        //abre modal de descarga de recibo (voucher)
        $this->confirmingVoucher = true;                                                                  
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
        $this->transaction->type = Transaction::PAGO; 
        $this->bank_account_id = "";
        $this->check_number = "";
                 
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
        $transaction->date = Carbon::parse($transaction->date)->format('d/m/Y'); //formatea la fecha       
        
        //genera el pdf con el contenido de la transacción
        $pdf = PDF::loadView('transactions.voucher', ['transaction' => $transaction]);    
        //muestra el pdf    
        return $pdf->stream();                  
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

            $newtotal = $amount + $total ;
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
        $nuevoNumero = 2000001;

        $lastRegister = Transaction::where('type', Transaction::PAGO)->latest('id')->first();
        
        if ($lastRegister) {
            $this->number = intval($lastRegister->number);
            $nuevoNumero = $this->number + 1;
        }                             
        return $nuevoNumero;
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
