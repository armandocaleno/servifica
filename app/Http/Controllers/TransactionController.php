<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Partner;
use App\Models\Transaction;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    public $transactions_list;   

    public function index()
    {
        return view('transactions.index');
    }   

    public function store(Request $request)
    {        
        $account_ = Account::find($request->account_id);
        $accounts = Account::all(); //obtiene todas las cuentas

        
               
        foreach ($request->selected_partner as $key => $selected) {
            
            foreach ($request->partner_id as $key2 => $value2) {
                
                if ($request->partner_id[$key2] == $request->selected_partner[$key]) {
                    $saldo = 0;
                    $newtotal = 0;

                    Cart::instance('new')->destroy();
                    Cart::instance('voucher')->destroy();

                    $transaction = new Transaction();
                    $transaction->partner_id = $request->partner_id[$key2];
                    $transaction->total = $request->amounts[$key2];
                    $transaction->date = $request->date;
                    $transaction->reference = $request->reference;
                    $transaction->type = Transaction::LOTES;

                    $account_partner = Partner::find($request->partner_id[$key2])->accounts()->where('account_id', $request->account_id)->get();            

                    if ($account_partner->count()) {                
                        $saldo = $account_partner[0]->pivot->value;
                    }else {
                        $saldo = "0.00";
                    }

                    //obtiene el socios de la transacción actual
                    $partner = Partner::find($request->partner_id[$key2]);

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
                    
                    //valida la transacción actual
                    Validator::make($request->all(), [                        
                        'date' => 'required|date',                        
                        'reference' => 'nullable|max:255',                        
                        'amounts.'.$key2 => 'bail|required|numeric|regex:/^[\d]{0,4}(\.[\d]{1,2})?$/',                        
                        'partner_id.'.$key2 => 'required'
                    ],[
                        'amounts.*' => 'El monto no es válido para el socio ' . $partner->name ." ". $partner->lastname
                    ])->validate();                    
                        
                    //establece el saldo de la cuenta del socio.
                    $newtotal = $saldo + $request->amounts[$key2];                    
                                                                                          
                    Cart::instance('new')->add([
                        'id' => $request->account_id,
                        'name' => $account_->name,
                        'qty' => 1,
                        'price' => $request->amounts[$key2],
                        'weight' => 100,
                        'options' => ['previus' => $saldo, 'new' => $newtotal]        
                    ]);   

                    $transaction->content = Cart::instance('new')->content();   
                    
                    //actualiza las cuentas del voucher que están en la trasacción actual
                    foreach (Cart::instance('voucher')->content() as $voucher) {
                        foreach ($transaction->content as $value ) {
                            if ($voucher->id == $value['id']) {                                
                                Cart::instance('voucher')->update($voucher->rowId,[                                                                                    
                                    'price' => $value['price'],                            
                                    'options' => ['previus' => $value['options']['previus'], 'new' => $value['options']['new']]        
                                ]);  
                            }                                          
                        }
                    }    

                    //guarda los rubros de todas las cuentas en el objeto
                    $transaction->voucher = Cart::instance('voucher')->content();    

                    $this->transactions_list[] = $transaction;                                                    
                }
            }            
        }

        foreach ($this->transactions_list as $transaction) {
            Transaction::create([
                'date' => $transaction->date,
                'number' => $this->GenTransactionNumber($transaction->type),
                'partner_id' => $transaction->partner_id,
                'reference' => $transaction->reference,
                'total' => $transaction->total,                
                'content' => $transaction->content,
                'voucher' => $transaction->voucher,
                'type' => $transaction->type,
                'company_id' => session('company')->id
            ]); 

            //Registra o actualiza saldos en cuentas
            $this->totalUpdate($transaction);
        }                                

        return redirect()->route('transactions.index');        
    }

    public function create()
    {
        $partners = Partner::all();
        $transaction = new Transaction();

        return view('transactions.create', compact('partners', 'transaction'));
    }    

    public function edit($transaction_id)
    {
        $partners = Partner::all();

        $transaction = Transaction::find($transaction_id);
        
        return view('transactions.create', compact('partners', 'transaction'));
    }    

    public function lotes()
    {        
       return view('transactions.lotes'); 
    }

    //Generar el número de transacción
    public function GenTransactionNumber()
    {                
        $nuevoNumero = 1000001;

        $lastRegister = Transaction::whereIn('type', [Transaction::INDIVIDUAL, Transaction::LOTES])->latest('id')->first();

        if ($lastRegister) {
            $this->number = intval($lastRegister->number);
            $nuevoNumero = $this->number + 1;
        }       
                        
        return $nuevoNumero;
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

    public function payment()
    {
        $partners = Partner::all();
        $transaction = new Transaction();

        return view('transactions.payment', compact('partners', 'transaction'));
    }
}
