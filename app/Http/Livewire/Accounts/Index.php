<?php

namespace App\Http\Livewire\Accounts;

use App\Models\Account;
use App\Models\Partner;
use Livewire\Component;

class Index extends Component
{
    public $search, $sort, $direction;
    public $confirmingDeletion;
    public $account;

    public function mount()
    {                   
        $this->sort = "id";
        $this->direction = "desc";
        $this->search = "";        
        $this->confirmingDeletion = false;        
    }

    public function render()
    {
        $accounts = Account::where('name', 'LIKE', '%' . $this->search . '%')
                            ->orderBy($this->sort, $this->direction)->paginate(10);       

        return view('livewire.accounts.index', compact('accounts'));
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

    public function delete(Account $account)
    {
        $this->account = $account;   
        $this->confirmingDeletion = true;     
    }

    public function destroy()
    {                               
        $accounts = Partner::whereRelation('accounts','account_id',$this->account->id)->get();
        
        if(count($accounts)){            
            // Mensaje enviado a la vista
            $this->info('No se puede eliminar esta cuenta porque tiene transacciones asociadas.');  
        }else {
            $this->account->delete();
            // Mensaje enviado a la vista
            $this->success('Registro eliminado correctamente.');
        }

        $this->confirmingDeletion = false;        
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
