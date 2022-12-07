<?php

namespace App\Http\Livewire\Reporting;

use App\Models\Partner;
use App\Models\Transaction;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class Transactions extends Component
{
    use WithPagination;

    public $from_date, $to_date, $partner_id, $type;

    public function mount()
    {                      
        $this->from_date = Carbon::now()->format('Y-m-d');
        $this->to_date = Carbon::now()->format('Y-m-d');  
        
        $this->partner_id = "";
    }

    public function render()
    {
        $partners = Partner::where('status', Partner::ACTIVO)->get();                      

        if ($this->type == 0) {
            $transactions = Transaction::join('partners', 'partners.id', 'transactions.partner_id')
            ->select(['transactions.*', 'partners.name'])
            ->where('transactions.company_id', session('company')->id)   
            ->whereBetween('date', [$this->from_date, $this->to_date])        
            ->partner($this->partner_id)                      
            ->orderBy('date')->paginate(10); 
        } else {
            $transactions = Transaction::join('partners', 'partners.id', 'transactions.partner_id')
            ->select(['transactions.*', 'partners.name'])
            ->where('transactions.company_id', session('company')->id)  
            ->whereBetween('date', [$this->from_date, $this->to_date])
            ->where('type', $this->type)
            ->partner($this->partner_id)                      
            ->orderBy('date')->paginate(10); 
        }
        

        return view('livewire.reporting.transactions', compact('transactions', 'partners'));
    }
}
