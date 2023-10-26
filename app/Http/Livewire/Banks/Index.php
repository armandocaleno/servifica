<?php

namespace App\Http\Livewire\Banks;

use Livewire\WithPagination;
use App\Models\Bank;
use App\Models\BankAccount;
use Livewire\Component;

class Index extends Component
{
    use WithPagination;

    public $bank, $openCreateModal, $confirmingDeletion;

    protected $rules = [
        'bank.name' => 'required|unique:banks,name'
    ];

    public function mount(Bank $bank)
    {
        $this->bank = $bank;

        $this->openCreateModal = false;
        $this->confirmingDeletion = false;
    }

    public function render()
    {
        $banks = Bank::paginate(10);

        return view('livewire.banks.index', compact('banks'));
    }

    public function create()
    {
        $this->bank = new Bank();

        $this->openCreateModal = true;
    }

    public function edit(Bank $bank)
    {
        $this->bank = $bank;

        $this->openCreateModal = true;
    }

    public function save()
    {
        $rules = $this->rules;

        if ($this->bank->id) {

            $rules = [
                'bank.name' => 'required|unique:banks,name,'. $this->bank->id,
            ];
        }

        $this->validate($rules);

        if ($this->bank->id) {
            $this->bank->save();

            $this->success('Registro actualizado correctamente.');
        } else {
            Bank::create([
                'name' => $this->bank->name
            ]);

            $this->success('Registro creado correctamente.');
        }

        $this->openCreateModal = false;
        $this->render();
    }

    public function delete(Bank $bank)
    {
        $this->bank = $bank;

        $this->confirmingDeletion = true;
    }

    public function destroy()
    {
        $accounts = BankAccount::where('bank_id', $this->bank->id)->get();

        if ($accounts->count()) {
            $this->info('No se puede eliminar este banco porque tiene asociada una cuenta bancaria.');
        }else {
            $this->bank->delete();

            $this->success('Registro eliminado correctamente.');
            $this->render();
        }

        $this->confirmingDeletion = false;
    }

    // Mensaje de confirmación de acción
    public function success($message)
    {
        $this->dispatchBrowserEvent(
            'success',
            [
                'message' => $message,
            ]
        );
    }

    // Mensaje de confirmación de acción
    public function info($message)
    {
        $this->dispatchBrowserEvent(
            'info',
            [
                'message' => $message,
            ]
        );
    }
}
