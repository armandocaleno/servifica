<?php

namespace App\Http\Livewire\Partners;

use App\Models\Partner;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search, $sort, $direction;
    public $openDetailModal, $confirmingDeletion;
    public $partner;

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
        $partners = Partner::where('status', Partner::ACTIVO)
                            ->where('company_id', session('company')->id)
                            ->where(function($q){
                                $q->where('name', 'LIKE', '%' . $this->search . '%')
                                ->orWhere('lastname', 'LIKE', '%' . $this->search . '%')
                                ->orWhere('code', 'LIKE', '%' . $this->search . '%')
                                ->orWhere('identity', 'LIKE', $this->search . '%');
                            })                                                                                    
                            ->orderBy($this->sort, $this->direction)->paginate(10);

        return view('livewire.partners.index', compact('partners'));
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

    public function delete(Partner $partner)
    {
        $this->partner = $partner;   
        $this->confirmingDeletion = true;     
    }

    public function destroy()
    {                       
        $this->partner->update(['status'=> '0']);
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
