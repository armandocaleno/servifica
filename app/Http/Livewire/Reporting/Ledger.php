<?php

namespace App\Http\Livewire\Reporting;

use App\Models\Accounting;
use App\Models\JournalDetail;
use Carbon\Carbon;
use Livewire\Component;

class Ledger extends Component
{
    public $from_date, $to_date, $journals, $accounting_id, $account_type;

    public function mount()
    {                      
        // $this->from_date = Carbon::now()->format('Y-m-d');
        // $this->to_date = Carbon::now()->format('Y-m-d');  
        $this->accounting_id = "";
        $this->journals = array();
    }

    public function render()
    {
        $accountings = Accounting::where('company_id', session('company')->id)
                                    ->where('group', '0')
                                    ->orderBy('code')->get();
     
        return view('livewire.reporting.ledger', compact('accountings'));
    }

    function loadLedger() {
        if ($this->accounting_id == null || $this->accounting_id == "") {
            $this->info('Debe seleccionar una cuenta contable.');
            return;
        }

        $this->account_type = Accounting::find($this->accounting_id)->type;
        
        $this->journals = JournalDetail::join('journals', 'journals.id', 'journal_details.journal_id')
                                ->select('journals.date', 'journals.refence as reference', 'journal_details.debit_value', 'journal_details.credit_value')
                                ->where('journals.company_id', session('company')->id)
                                ->where('journals.state', '1')
                                ->whereBetween('journals.date', [$this->from_date, $this->to_date])
                                ->where('journal_details.accounting_id', $this->accounting_id)
                                ->orderBy('journals.date')
                                ->get();
        
        if (count($this->journals) == 0) {
            $this->info('No hay datos para mostrar.');
        }
    }

    function updatedAccountingId() {
        $this->journals = array();

        $this->loadLedger();
    }

    function updatedFromDate() {
        $this->journals = array();

        $this->loadLedger();
    }

    function updatedToDate() {
        $this->journals = array();

        $this->loadLedger();
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
