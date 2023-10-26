<?php

namespace App\Http\Livewire\Suppliers;

use App\Models\Supplier;
use Livewire\WithPagination;
use Livewire\Component;

class Index extends Component
{
    use WithPagination;

    public $search, $sort, $direction;
    public $openDetailModal, $confirmingDeletion;
    public $supplier;

    public function mount()
    {                   
        $this->sort = "id";
        $this->direction = "desc";
        $this->search = "";
        $this->openDetailModal = false;
        $this->confirmingDeletion = false;        
    }

    public function render()
    {
        $suppliers = Supplier::where('company_id', session('company')->id)
                            ->where(function($q){
                                $q->where('name', 'LIKE', '%' . $this->search . '%')
                                ->orWhere('identity', 'LIKE', $this->search . '%');
                            })                                                                                    
                            ->orderBy($this->sort, $this->direction)->paginate(10);

        return view('livewire.suppliers.index', compact('suppliers'));
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

    public function delete(Supplier $supplier)
    {
        $this->supplier = $supplier;   
        $this->confirmingDeletion = true;     
    }

    public function destroy()
    {                       
        // $this->partner->update(['status'=> '0']);
        $this->confirmingDeletion = false;

        // Mensaje enviado a la vista
        $this->success('Registro eliminado correctamente.');
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
