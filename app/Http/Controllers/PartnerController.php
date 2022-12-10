<?php

namespace App\Http\Controllers;

use App\Models\Accounting;
use App\Models\Partner;
use Illuminate\Http\Request;

class PartnerController extends Controller
{           
    public function index()
    {        
        return view('partners.index');
    }

    public function create()
    {        
        $partner = new Partner();                
        
        return view('partners.create', compact('partner'));
    }

    public function store(Request $request)
    {                
        $request->validate([
            'code' => 'required|max:15|unique:partners',
            'name' => 'required|max:25',
            'lastname' => 'required|max:25',            
            'identity' => 'required|max:15|unique:partners,identity',
            'phone' => 'nullable|max:15',
            'email' => 'nullable|email',
        ]);
        
        Partner::create([
            'code' => $request->code,
            'name' => $request->name,
            'lastname' => $request->lastname,
            'identity' => $request->identity,
            'phone' => $request->phone,
            'email' => $request->email,
            'accounting_id' => Accounting::first()->id,
            'company_id' => session('company')->id
        ]);

        return redirect()->route('partners.index');
    }

    public function edit(Partner $partner)
    {
        return view('Partners.edit', compact('partner'));
    }

    public function update(Request $request)
    {                        
        $request->validate([
            'code' => 'required|max:15|unique:partners,code,'.$request->id,
            'name' => 'required|max:25',
            'lastname' => 'required|max:25',
            'identity' => 'required|max:15|unique:partners,identity,'.$request->id,
            'phone' => 'nullable|max:15',
            'email' => 'nullable|email',
        ]);               

        $partner = Partner::find($request->id);

        $partner->update($request->all());

        return redirect()->route('partners.index');
    }
}
