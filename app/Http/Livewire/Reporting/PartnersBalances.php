<?php

namespace App\Http\Livewire\Reporting;

use App\Models\Account;
use App\Models\Partner;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class PartnersBalances extends Component
{
    public $partner_id, $partner, $search;

    public function mount()
    {
        $this->search = "";        
        $this->partner = new Partner();
    }

    public function render()
    {                
        $accounts = Account::all();
        $partners = Partner::where('status', Partner::ACTIVO)->get();

        $subquery = "";
        foreach ($accounts as $account) {
            $subquery .= '(SELECT ap.value FROM account_partner ap WHERE ap.partner_id = p.id AND ap.account_id = '. $account->id .') as "cuenta' . $account->id .'" ,';
        }
        $subquery = trim($subquery, ',');
        
        $balances = DB::select('SELECT p.id, p.code, p.name, p.lastname, p.identity, '. $subquery .' FROM partners p WHERE p.company_id = '.session('company')->id.' and p.status = 1 and (p.name LIKE "%'.$this->search.'%" or p.code LIKE "'.$this->search.'%" or p.identity LIKE "'.$this->search.'%")');               
                
        return view('livewire.reporting.partners-balances', compact('partners', 'accounts', 'balances'));
    }

    public function updatedPartnerId($value)
    {
        $this->partner = new Partner();
        $this->partner = Partner::find($value);               
    }
}
