<?php

namespace App\Http\Livewire\Reporting;

use App\Models\Account;
use App\Models\Partner;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Component;
use Livewire\WithPagination;

class Transactions extends Component
{
    use WithPagination;

    public $from_date, $to_date, $partner_id, $type, $account_id, $total;
    public $currentPage, $perPage;


    public function mount()
    {                      
        $this->from_date = Carbon::now()->format('Y-m-d');
        $this->to_date = Carbon::now()->format('Y-m-d');  
        
        $this->partner_id = "";
        $this->account_id = "";
        $this->total = 0;
        $this->currentPage = 1;
        $this->perPage = 5;
    }

    public function render()
    {
        $this->total = 0;
        $partners = Partner::where('status', Partner::ACTIVO)->get();    
        $accounts = Account::all();              
        $selected_transactions = [];
   
        if ($this->type == 0) {
            $trans = Transaction::join('partners', 'partners.id', 'transactions.partner_id')
            ->select(['transactions.*', 'partners.name'])
            ->where('transactions.company_id', session('company')->id)   
            ->whereBetween('date', [$this->from_date, $this->to_date])
            ->partner($this->partner_id)   
            ->orderBy('id')                   
            ->orderBy('date')->get(); 
        } else {
            $trans = Transaction::join('partners', 'partners.id', 'transactions.partner_id')
            ->select(['transactions.*', 'partners.name'])
            ->where('transactions.company_id', session('company')->id)  
            ->whereBetween('date', [$this->from_date, $this->to_date])
            ->where('type', $this->type)
            ->partner($this->partner_id)
            ->orderBy('id')                   
            ->orderBy('date')->get(); 
        }

        foreach ($trans as $value) {
            if ($this->account_id != "") {
                foreach ($value->content as $c) {
                    if ($c['id'] == $this->account_id) {
                        $selected_transactions[] = $value;
                    }
                }
            }else {
                $selected_transactions[] = $value;
            }

            $this->total += $value['total'];
        }

        $this->currentPage = LengthAwarePaginator::resolveCurrentPage();

        $currentElements = array_slice($selected_transactions, $this->perPage * ($this->currentPage - 1), $this->perPage, true);
        
        $transactions = new LengthAwarePaginator($currentElements, count($selected_transactions), $this->perPage, $this->currentPage);

        return view('livewire.reporting.transactions', compact('transactions', 'partners', 'accounts'));
    }
}
