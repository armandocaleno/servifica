<?php

namespace App\Http\Livewire\Transaction;

use App\Models\Account;
use App\Models\Partner;
use App\Models\Transaction;
use Carbon\Carbon;
use Gloudemans\Shoppingcart\Facades\Cart;
use Livewire\Component;

class Lotes extends Component
{    
    public $date, $reference, $amount, $account_id, $account, $transactions_list, $partners;

    protected $rules = [
        'transaction.date' => 'required|date',
        'transaction.number' => 'required|max:15',
        'transaction.reference' => 'max:255',
        'transaction.total' => 'required|numeric|regex:/^[\d]{0,4}(\.[\d]{1,2})?$/',        
    ];

    public function mount()
    {                                      
        $this->date = Carbon::now()->format('Y-m-d');
        $this->reference = "";
        $this->account_id = 0;
        $this->amount = "0.00";            

        $this->transactions_list = [];
        $this->partners = [];

        Cart::instance('old')->destroy();
        Cart::instance('new')->destroy();            
    }

    public function render()
    {
        $accounts = Account::where('type', Account::INGRESO)->get();
       
        return view('livewire.transaction.lotes', compact('accounts'));
    }      

    public function partnersLoad()
    {
        if ($this->amount < 1) {
            $this->info('Monto incorrecto!');    
            return;
        }

        if ($this->account_id < 1) {
            $this->info('Seleccione una cuenta primero!');    
            return;
        }       
        
        $this->account = Account::find($this->account_id);                                                               
        
        $this->partners = Partner::where('company_id', session('company')->id)->where('status', Partner::ACTIVO)->get();

        if (count($this->partners) == 0) {
            $this->info('No hay socios registrados!');    
            return;
        }
    }   

    public function updatedAccountId($value)
    {        
        if ($value > 0) {
            $this->account = Account::find($value);
        }
    }
   
    // public function save()
    // {        
    //     $this->transaction->content = Cart::instance('new')->content();    
    //     $this->transaction->total = Cart::instance('new')->subtotal();

    //     if ($this->transaction->id) {
    //         $this->transaction->save();
    //     } else {
    //         $this->validate();

    //         $transaction = Transaction::create([
    //             'date' => $this->transaction->date,
    //             'number' => $this->transaction->number,
    //             'reference' => $this->transaction->reference,
    //             'total' => $this->transaction->total,                
    //             'content' => $this->transaction->content,
    //             'type' => Transaction::LOTES
    //         ]);

    //         $partners = Partner::where('status', Partner::ACTIVO)->get();

    //         foreach ($partners as $partner) {
    //             $transaction->partners()->attach($partner->id);

    //             //Registrar saldos en cuentas
    //             $this->totalUpdate($transaction, $partner->id);
    //         }                        
    //     }

    //     // // Mensaje al usuario
    //     $this->success('Guardado exitosamente.');       
       
    //      // Reset controles
    //     Cart::instance('old')->destroy();
    //     Cart::instance('new')->destroy();                
                             
    //     return redirect()->route('transactions.index');
        
    // }

    public function totalUpdate(Transaction $transaction, $partner_id)
    {                   
        //Obtengo el contenido de la transacción
        $content = $transaction->content;
        
        //Obtengo el socio de la transacción
        $partner = Partner::find($partner_id);            

        foreach ($content as $value) {                                   
            //Obtengo el valor de la cuenta
            $amount = $value['price'];                                         
            
            //Consulto si el socio ya tiene la cuenta ingresada.
            $account_partner = $partner->accounts()->find($value['id']);

            //si ya existe sumo el valor actual con el existente y actualizo la tabla relacionada.
            if ($account_partner) {
                $value = $account_partner->pivot->value;
                $newtotal = $amount + $value;
                
                $partner->accounts()->updateExistingPivot($account_partner->id, ['value' => $newtotal]);
            } else {                           
                //si no existe inserto la cuenta con el valor respectivo.                
                $partner->accounts()->attach($value['id'], ['value' => $amount]);
            }                        
        }                                        
    }

     //Generar el número de transacción
     public function GenTransactionNumber()
     {                
         $lastRegister = Transaction::latest('id')->first();
 
         if ($lastRegister) {
             $this->number = intval($lastRegister->id);
         } else {
             $this->number = 0;
         }        
         
         $nuevoNumero = $this->number + 1000001;
         // $nuevoNumero = substr(str_repeat(0, 6).$nuevoNumero, - 6);        
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
