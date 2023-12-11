<?php

namespace App\Http\Livewire\BankAccounts;

use App\Models\Accounting;
use App\Models\Bank;
use App\Models\BankAccount;
use App\Models\JournalDetail;
use Livewire\WithPagination;
use Livewire\Component;

class Index extends Component
{
    use WithPagination;

    public $bankaccount, $openCreateModal, $confirmingDeletion;

    protected $rules = [
        'bankaccount.number' => 'nullable|unique:bank_accounts,number',
        'bankaccount.owner' => 'nullable',
        'bankaccount.type' => 'required',
        'bankaccount.reference' => 'required',
        'bankaccount.bank_id' => 'nullable',
        'bankaccount.accounting_id' => 'required',
    ];

    public function mount()
    {
        $this->openCreateModal = false;
        $this->confirmingDeletion = false;
    }

    public function render()
    {
        $bankaccounts = BankAccount::where('company_id', session('company')->id)->paginate(10);

        $banks = Bank::all();
        $accountings = Accounting::where('company_id', session('company')->id)
                                    ->where('group', '0')
                                    ->orderBy('account_class_id')
                                    ->orderBy('code')
                                    ->get();

        return view('livewire.bank-accounts.index', compact('bankaccounts', 'banks', 'accountings'));
    }

    public function create()
    {
        $this->bankaccount = new BankAccount();
        $this->bankaccount->type = BankAccount::AHORRO;

        $this->openCreateModal = true;
    }

    public function edit(BankAccount $bankaccount)
    {
        $this->bankaccount = $bankaccount;

        $this->openCreateModal = true;
    }

    public function save()
    {
        $rules = $this->rules;
      
        if ($this->bankaccount->id) {

            $rules = [
                'bankaccount.number' => 'nullable|unique:bank_accounts,number,'. $this->bankaccount->id,
            ];
        }

        $this->validate($rules);

        if ($this->bankaccount->id) {

            $this->bankaccount->save();

            $this->success('Registro actualizado correctamente.');
        } else {

            BankAccount::create([
                'number' => $this->bankaccount->number,
                'owner' => $this->bankaccount->owner,
                'type' => $this->bankaccount->type,
                'bank_id' => $this->bankaccount->bank_id,
                'accounting_id' => $this->bankaccount->accounting_id,
                'company_id' => session('company')->id
            ]);

            $this->success('Registro creado correctamente.');
        }

        $this->openCreateModal = false;
        $this->render();
    }

    public function delete(BankAccount $bankaccount)
    {
        $this->bankaccount = $bankaccount;

        $this->confirmingDeletion = true;
    }

    public function destroy()
    {
        $journals = JournalDetail::where('accounting_id', $this->bankaccount->accounting_id)->get();
       
        if ($journals->count()) {
            $this->info('No se puede eliminar esta cuenta porque tiene asociada asientos contables.');
        }else {
            $this->bankaccount->delete();

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
