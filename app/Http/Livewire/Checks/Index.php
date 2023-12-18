<?php

namespace App\Http\Livewire\Checks;

use App\Models\Check;
use App\Models\Journal;
use App\Models\JournalDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use PDF;

class Index extends Component
{
    use WithPagination;

    public $search, $sort, $direction;
    public $confirmingDeletion;
    public $check;

    public function mount()
    {                   
        $this->sort = "id";
        $this->direction = "desc";
        $this->search = "";        
        $this->confirmingDeletion = false;        
    }

    public function render()
    {
        $checks = Check::join('bank_accounts', 'bank_accounts.id', 'checks.bank_account_id',)
        ->where('bank_accounts.company_id', session('company')->id)
        ->where(function($q){
            $q->where('checks.number', 'LIKE', '%' . $this->search . '%') 
            ->orWhere('beneficiary', 'LIKE', '%' . $this->search . '%')       
            ->orwhere('checks.reference', 'LIKE', '%' . $this->search . '%') ;                        
        })
        ->orderBy($this->sort, $this->direction)
        ->select('checks.*')->paginate(10);
    
        return view('livewire.checks.index', compact('checks'));
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

    public function delete(Check $check)
    {
        $this->check = $check;   
        $this->confirmingDeletion = true;     
    }

    function destroy() {

        DB::beginTransaction();

        try {
            // Elimina la relacion con las facturas de gastos si la tuviere
            if($this->check->expenses){
                $this->check->expenses()->detach($this->check);
            }

            //Crea el asiento reverso de anulacion
            $journal = $this->check->journal;

            $debit_account =  "";
            $credit_account = "";

            if ($journal != null) {
                foreach ($journal->details as $detail) {
                    if ($detail->debit_value > 0) {
                        $debit_account = $detail->accounting;
                    } else {
                        $credit_account = $detail->accounting;
                    }
                    
                }
            }else {
                $this->info('Este cheque ya está anulado.');
                return;
            }

            $newjournal = Journal::create([
                'number' => $this->GenjournalNumber(),
                'date' => Carbon::now(),
                'refence' => "Anulación - Cheque: " .  $this->check->number . " - Beneficiario: " . $this->check->beneficiary,
                'company_id' => session('company')->id,
                'type' => Journal::AUTO,
                'journable_id' => $this->check->id,
                'journable_type' => check::class
            ]);

            JournalDetail::create([
                'journal_id' => $newjournal->id,
                'accounting_id' => $debit_account->id,
                'debit_value' => 0,
                'credit_value' => $this->check->total
            ]);

            JournalDetail::create([
                'journal_id' => $newjournal->id,
                'accounting_id' => $credit_account->id,
                'debit_value' => $this->check->total,
                'credit_value' => 0
            ]);

            //Actualiza el estado del cheque
            $this->check->update(['status' => '0']);
            $this->success('Cheque eliminado correctamente.');
   
            DB::commit();

            $this->confirmingDeletion = false;  
        } catch (\Throwable $th) {
            DB::rollback();
            $this->info('Hubo un error y no se eliminó el cheque.');
            $this->info($th->getMessage());
        }
    }

    //Generar el número de asiento
    public function GenjournalNumber()
    {                
        $j = new Journal();
        $number = $j->getNumber();
        return $number;
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
