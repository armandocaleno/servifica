<?php

namespace App\Http\Livewire\AccountingConfig;

use App\Models\Accounting;
use App\Models\AccountingConfig;
use Livewire\Component;

class Index extends Component
{
    public $openCreateModal, $accounting_config;

    protected $rules = ['accounting_config.accounting_id' => 'nullable'];

    public function mount()
    {
        $this->openCreateModal = false;
    }
    public function render()
    {
        $accountingConfig = AccountingConfig::where('company_id', session('company')->id)->get();
        $accountings = Accounting::where('company_id', session('company')->id)
                                    ->where('group', '0')
                                    ->orderBy('account_class_id')
                                    ->orderBy('code')
                                    ->get();

        return view('livewire.accounting-config.index', compact('accountingConfig', 'accountings'));
    }

    public function edit(AccountingConfig $accounting_config)
    {
        $this->accounting_config = $accounting_config;

        $this->openCreateModal = true;
    }

    public function save()
    {
        $this->accounting_config->save();

        $this->openCreateModal = false;  

        // Mensaje enviado a la vista
        $this->success('Registro actualizado correctamente.');
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
