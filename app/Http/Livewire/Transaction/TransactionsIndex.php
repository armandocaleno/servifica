<?php

namespace App\Http\Livewire\Transaction;

use App\Models\Account;
use App\Models\Partner;
use App\Models\Transaction;
use Gloudemans\Shoppingcart\Facades\Cart;
use Livewire\Component;
use Livewire\WithPagination;

class TransactionsIndex extends Component
{
    use WithPagination;

    public $search, $sort, $direction, $transaction, $detail, $aditional_info;
    public $openDetailModal, $confirmingDeletion;
    public $partner;

    public function mount()
    {
        $this->transaction = [];
        $this->detail = [];               
        $this->sort = "transactions.id";
        $this->direction = "desc";
        $this->search = "";
        $this->openDetailModal = false;
        $this->confirmingDeletion = false;

        Cart::instance('old')->destroy();
        Cart::instance('new')->destroy();
    }

    public function render()
    {                                                
        $transactions = Transaction::join('partners', 'partners.id', 'transactions.partner_id')
        ->select(['transactions.*', 'partners.name'])
        ->where('transactions.company_id', session('company')->id)
        ->where(function($q){
            $q->where('number', 'LIKE', '%' . $this->search . '%') 
            ->orWhere('name', 'LIKE', '%' . $this->search . '%')       
            ->orwhere('reference', 'LIKE', '%' . $this->search . '%') ;                        
        })
        ->orderBy($this->sort, $this->direction)->paginate(10);                      

        return view('livewire.transaction.transactions-index', compact('transactions'));
    }

    public function order($sort){
        if ($this->sort == $sort) {
            if ($this->direction == 'desc') {
                $this->direction = 'asc';
            } else {
                $this->direction = 'desc';
            }
            
        }else{
            $this->sort = $sort;
            $this->direction = 'desc';
        }        
    }

    public function updatingSearch()
    {
        $this->resetPage();         
    }

    public function showDetail(Transaction $transaction)
    {                                
        $this->detail = $transaction->content; 
        $this->aditional_info = $transaction->aditional_info; 
        
        if ($this->detail) {
            sort($this->detail, SORT_NUMERIC); //se ordena el array por el id
        }
        
        $this->openDetailModal = true;                
    }

    public function delete(Transaction $transaction)
    {
        $this->transaction = $transaction;   
        $this->confirmingDeletion = true;     
    }

    public function destroy()
    {               
        //actualiza los saldos de la cuentas de la transacción a eliminar
        $this->totalUpdate($this->transaction);

        //elimina la transacción
        $this->transaction->delete();

        //cierra el modal
        $this->confirmingDeletion = false;

        // Mensaje enviado a la vista
        $this->success('Registro eliminado correctamente.');
    }    

    public function totalUpdate(Transaction $transaction)
    {                   
        //setea el contenido de la transacción
        $content = $transaction->content;
        
        //Obtiene el socio de la transacción
        $partner = Partner::find($transaction->partner_id);  
                        
        foreach ($content as $value) {                                                    
            //setea el saldo de la cuenta
            $total = $value['price'];                                         
            
            //Consulta si el socio ya tiene la cuenta ingresada.
            $account_partner = $partner->accounts()->find($value['id']);            

            //consulta la cuenta asociada
            $account = Account::find($value['id']);
            //setea el valor de cuenta existente
            $amount = $account_partner->pivot->value;

            $newtotal = $amount + $total ;
            if ($transaction->type == Transaction::LOTES || $account->type == Account::EGRESO) {
                $newtotal = $amount - $total;
            }
            
            //si ya existe actualiza la tabla relacionada con el nuevo saldo.
            if ($account_partner) {                                               
                $partner->accounts()->updateExistingPivot($account_partner->id, ['value' => $newtotal]);
            }                     
        }                                        
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
}
