<?php

namespace App\Http\Livewire\Expenses;

use App\Models\Expense;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search, $sort, $direction;
    public $confirmingDeletion;
    public $expense;

    public function mount()
    {                   
        $this->sort = "id";
        $this->direction = "desc";
        $this->search = "";        
        $this->confirmingDeletion = false;        
    }

    public function render()
    {
        $expenses = Expense::where('company_id', session('company')->id)
            ->where(function($q){
                $q->where('number', 'LIKE', '%' . $this->search . '%') 
                // ->orWhere('supplier', 'LIKE', '%' . $this->search . '%')       
                ->orwhere('reference', 'LIKE', '%' . $this->search . '%') ;                        
            })
            ->orderBy($this->sort, $this->direction)->paginate(10);

        return view('livewire.expenses.index', compact('expenses'));
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

    public function delete(Expense $expense)
    {
        $this->expense = $expense;   
        $this->confirmingDeletion = true;     
    }

    public function destroy()
    {             
        if ($this->expense != null) {
            if ($this->expense->journal != null) {
                $this->expense->journal->delete();
            }  

            $this->expense->delete();
        }          
        
        $this->confirmingDeletion = false;  

        // Mensaje enviado a la vista
        $this->success('Registro eliminado correctamente.');
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
