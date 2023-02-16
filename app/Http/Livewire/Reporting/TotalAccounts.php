<?php

namespace App\Http\Livewire\Reporting;

use App\Models\Account;
use App\Models\Partner;
use App\Models\Transaction;
use Carbon\Carbon;
use Livewire\Component;

class TotalAccounts extends Component
{
    public $from_date, $to_date, $partner_id, $type, $account_id, $total;

    public function mount()
    {                      
        $this->from_date = Carbon::now()->format('Y-m-d');
        $this->to_date = Carbon::now()->format('Y-m-d');  
        
        $this->partner_id = "";
        $this->account_id = "";
        $this->total = 0;
    }

    public function render()
    {
        $partners = Partner::where('status', Partner::ACTIVO)->get();    
        $accounts = Account::all();              
        $transactions = [];
        $this->total = 0;

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
            ->partner($this->partner_id)                      
            ->orderBy('date')->paginate(10); 
        }

        foreach ($trans as $value) {
            foreach ($value->content as $c) {
                if ($this->account_id != "") {
                    if ($c['id'] == $this->account_id) {
                        $transactions[] = [
                            'number' => $value->number,
                            'date' => $value->date,
                            'partner_name' => $value->partner->name,
                            'partner_lastname' => $value->partner->lastname,
                            'account' => $c['name'],
                            'value' => $c['price'],
                            'type' => $value->type,
                        ];
                        $this->total += $c['price'];
                    }
                }
            }
        }

        return view('livewire.reporting.total-accounts', compact('transactions', 'partners', 'accounts'));
    }
}
