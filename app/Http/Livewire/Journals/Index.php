<?php

namespace App\Http\Livewire\Journals;

use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;
use App\Models\Journal;
use App\Models\JournalDetail;
use Carbon\Carbon;
use Livewire\Component;

class Index extends Component
{
    use WithPagination;
    public $to, $from, $search, $type, $journal;
    public  $detail, $openDetailModal, $openDeleteModal;

    public function mount()
    {
        $this->openDetailModal = false;
        $this->openDeleteModal = false;
        $this->from = Carbon::now()->format('Y-m-d');
        $this->to = Carbon::now()->format('Y-m-d');
        $this->search = "";
        $this->type = "";
    }

    public function render()
    {
        if ($this->type != "") {
            $query = "->where('type'," . $this->type . ")";
            $journals = Journal::where('company_id', session('company')->id)
            ->where('state', Journal::ACTIVO)
            ->whereBetween('date', [$this->from, $this->to])
            ->where('type', $this->type)
            ->Where(function ($q) {
                $q->orwhere('number', 'LIKE', $this->search . '%')
                    ->orwhere('refence', 'LIKE', '%' . $this->search . '%');
            })->orderBy('date')->paginate(5);
        }else {
            $journals = Journal::where('company_id', session('company')->id)
            ->where('state', Journal::ACTIVO)
            ->whereBetween('date', [$this->from, $this->to])
            ->Where(function ($q) {
                $q->orwhere('number', 'LIKE', $this->search . '%')
                    ->orwhere('refence', 'LIKE', '%' . $this->search . '%');
            })->orderBy('date')->paginate(5);
        }

        if ($this->search != "") {
            $journals = Journal::where('company_id', session('company')->id)
            ->where('state', Journal::ACTIVO)
                ->Where(function ($q) {
                    $q->where('number', 'LIKE', $this->search . '%')
                        ->orwhere('refence', 'LIKE', '%' . $this->search . '%');
                })->orderBy('date')->paginate(5);
        }

        return view('livewire.journals.index', compact('journals'));
    }

    public function showDetail(Journal $journal)
    {
        $this->detail = $journal->details;

        $this->openDetailModal = true;
    }

    function delete(Journal $journal) {
        $this->openDeleteModal = true;
        $this->journal = $journal;
    }

    function destroy() {
        $this->openDeleteModal = false;

        DB::beginTransaction();

        try {
            $this->journal->update([
                'state' => '0'
            ]);

            // Crea asiento de reverso
            $newjournal = Journal::create([
                'number' => $this->GenjournalNumber(),
                'date' => Carbon::now(),
                'refence' => 'Anulación de asiento ' . $this->journal->number,
                'company_id' => session('company')->id,
                'type' => Journal::AUTO,
                'journable_id' => 1,
                'journable_type' => Journal::class
            ]);

            foreach ($this->journal->details as $item) {
                $debit = 0; $credit = 0; 
                if ($item->debit_value > 0) {
                    $debit = $item->debit_value;
                }else{
                    $credit= $item->credit_value;
                }

                JournalDetail::create([
                    'journal_id' => $newjournal->id,
                    'accounting_id' => $item->accounting_id,
                    'debit_value' => $credit,
                    'credit_value' => $debit
                ]);
            }

            DB::commit();

            $this->success('Registro eliminado correctamente.');

        } catch (\Throwable $th) {
            //throw $th;
            DB::rollback();
            $this->info('Hubo un error y no se anuló el asiento.');
            $this->info($th->getMessage());
        }
    }

     //Generar el número de transacción
     public function GenjournalNumber()
     {                
         $j = new Journal();
         $number = $j->getNumber();
         return $number;
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

    // Mensaje de información
    public function info($message)
    {
        $this->dispatchBrowserEvent('info', 
            [
                'message' => $message,                       
            ]
        );       
    }
}
