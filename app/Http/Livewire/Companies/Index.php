<?php

namespace App\Http\Livewire\Companies;

use App\Models\Company;
use App\Models\Transaction;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search, $sort, $direction;
    public $confirmingDeletion;
    public $company;

    public function mount()
    {                   
        $this->sort = "id";
        $this->direction = "desc";
        $this->search = "";        
        $this->confirmingDeletion = false;        
    }

    public function render()
    {
        $companies = Company::where('businessname', 'LIKE', '%' . $this->search . '%')
                            ->orwhere('tradename', 'LIKE', '%' . $this->search . '%')
                            ->orderBy($this->sort, $this->direction)->paginate(10);

        // dd($companies);

        return view('livewire.companies.index', compact('companies'));
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

    public function delete(Company $company)
    {
        $this->company = $company;   
        $this->confirmingDeletion = true;     
    }

    public function destroy()
    {                               
        $transactions = Transaction::where('company_id', $this->company->id)->get();
        // dd($transactions);
        if(count($transactions)){            
            // Mensaje enviado a la vista
            $this->info('No se puede eliminar esta empresa porque tiene transacciones asociadas.');  
        }else {
            $this->company->delete();
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
