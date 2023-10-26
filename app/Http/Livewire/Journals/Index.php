<?php

namespace App\Http\Livewire\Journals;

use Livewire\WithPagination;
use App\Models\Journal;
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

        $this->journal->update([
            'state' => '0'
        ]);
        
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
