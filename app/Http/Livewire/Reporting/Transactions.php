<?php

namespace App\Http\Livewire\Reporting;

use App\Models\Account;
use App\Models\Partner;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Transactions extends Component
{
    use WithPagination;

    public $from_date, $to_date, $partner_id, $type, $account_id;

    public function mount()
    {                      
        $this->from_date = Carbon::now()->format('Y-m-d');
        $this->to_date = Carbon::now()->format('Y-m-d');  
        
        $this->partner_id = "";
        $this->account_id = "";
    }

    public function updatedFromDate()
    {
        $this->resetPage();
    }

    public function updatedToDate()
    {
        $this->resetPage();
    }

    public function updatedPartnerId()
    {
        $this->resetPage();
    }

    public function updatedType()
    {
        $this->resetPage();
    }

    public function render()
    {
        $partners = Partner::where('status', Partner::ACTIVO)->get();    
        $accounts = Account::all();              
        $transactions = [];
        if ($this->type == 0) {
            $trans = Transaction::join('partners', 'partners.id', 'transactions.partner_id')
            ->select(['transactions.*', 'partners.name'])
            ->where('transactions.company_id', session('company')->id)   
            ->whereBetween('date', [$this->from_date, $this->to_date])
            ->partner($this->partner_id)                      
            ->orderBy('date')->paginate(10); 
        } else {
            $trans = Transaction::join('partners', 'partners.id', 'transactions.partner_id')
            ->select(['transactions.*', 'partners.name'])
            ->where('transactions.company_id', session('company')->id)  
            ->whereBetween('date', [$this->from_date, $this->to_date])
            ->where('type', $this->type)
            // ->whereJsonContains('content->f6bddb674747fdae4a2c66e17666ec49->id', 2)
            ->partner($this->partner_id)                      
            ->orderBy('date')->paginate(10); 
        }

        foreach ($trans as $value) {
            foreach ($value->content as $c) {
                if ($this->account_id != "") {
                    if ($c['id'] == $this->account_id) {
                        $transactions[] = $value;
                    }
                }else {
                    $transactions[] = $value;
                }
            }
        }
        // dd($transactions);
        // =====================================================================================
        // $partners = Partner::where('status', Partner::ACTIVO)->get();                  

        // if ($this->type == 0) {
        //     $transactions = Transaction::join('partners', 'partners.id', 'transactions.partner_id')
        //     ->select(['transactions.*', 'partners.name'])
        //     ->where('transactions.company_id', session('company')->id)   
        //     ->whereBetween('date', [$this->from_date, $this->to_date])        
        //     ->partner($this->partner_id)                      
        //     ->orderBy('date')->paginate(10); 
        // } else {
        //     $transactions = Transaction::join('partners', 'partners.id', 'transactions.partner_id')
        //     ->select(['transactions.*', 'partners.name'])
        //     ->where('transactions.company_id', session('company')->id)  
        //     ->whereBetween('date', [$this->from_date, $this->to_date])
        //     ->where('type', $this->type)
        //     ->partner($this->partner_id)                      
        //     ->orderBy('date')->paginate(10); 
        // }

        return view('livewire.reporting.transactions', compact('transactions', 'partners', 'accounts'));
    }
}
