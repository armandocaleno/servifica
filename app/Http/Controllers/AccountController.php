<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Accounting;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index()
    {        
        return view('accounts.index');
    }

    public function create()
    {
        $account = new Account();
        $accountings = Accounting::where('company_id', session('company')->id)
                                ->where('group', '0')
                                ->orderBy('account_class_id')
                                ->orderBy('code')
                                ->get();

        return view('accounts.create', compact('account', 'accountings'));
    }

    public function store(Request $request)
    {        
        $request->validate([
            'name' => 'required|max:50|unique:accounts',                                  
            'type' => 'required',
            'accounting_id' => 'required|integer'         
        ]);
        
        Account::create($request->all());

        return redirect()->route('accounts.index');
    }

    public function edit(Account $account)
    {
        $accountings = Accounting::where('company_id', session('company')->id)
                                ->where('group', '0')
                                ->orderBy('account_class_id')
                                ->orderBy('code')
                                ->get();
        
        return view('accounts.edit', compact('account', 'accountings'));
    }

    public function update(Request $request)
    {                       
        $request->validate([
            'name' => 'required|max:50|unique:accounts,name,'.$request->id,                                  
            'type' => 'required',
            'accounting_id' => 'required|integer'        
        ]);

        $account = Account::find($request->id);

        $account->update($request->all());

        return redirect()->route('accounts.index');
    }    
}
