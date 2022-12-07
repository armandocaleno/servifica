<?php

namespace App\Http\Controllers;

use App\Models\Account;
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
        return view('accounts.create', compact('account'));
    }

    public function store(Request $request)
    {        
        $request->validate([
            'name' => 'required|max:50|unique:accounts',                                  
            'type' => 'required'            
        ]);
        
        Account::create($request->all());

        return redirect()->route('accounts.index');
    }

    public function edit(Account $account)
    {
        return view('accounts.edit', compact('account'));
    }

    public function update(Request $request)
    {                       
        $request->validate([
            'name' => 'required|max:50|unique:accounts,name,'.$request->id,                                  
            'type' => 'required'            
        ]);

        $account = Account::find($request->id);

        $account->update($request->all());

        return redirect()->route('accounts.index');
    }    
}
