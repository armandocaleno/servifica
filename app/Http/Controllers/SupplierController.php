<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        return view('suppliers.index');
    }

    public function create()
    {        
        $supplier = new Supplier();                
        
        return view('suppliers.create', compact('supplier'));
    }

    public function store(Request $request)
    {                
        $request->validate([
            'name' => 'required|max:25',     
            'identity' => 'required|numeric|unique:suppliers,identity',
            'phone' => 'nullable|max:15',
            'address' => 'nullable|max:125',
        ]);
        
        Supplier::create([
            'name' => $request->name,
            'identity' => $request->identity,
            'phone' => $request->phone,
            'address' => $request->address,
            'company_id' => session('company')->id
        ]);

        return redirect()->route('suppliers.index');
    }

    public function edit(Supplier $supplier)
    {
        return view('suppliers.edit', compact('supplier'));
    }

    public function update(Request $request)
    {                        
        $request->validate([
            'name' => 'required|max:25',
            'identity' => 'required|max:15|unique:suppliers,identity,'.$request->id,
            'phone' => 'nullable|max:15',
            'address' => 'nullable|max:125',
        ]);               

        $supplier = Supplier::find($request->id);

        $supplier->update($request->all());

        return redirect()->route('suppliers.index');
    }
}
