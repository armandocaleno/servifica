<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SelectCompanyController extends Controller
{
    
    public function index()
    {
        $companies = Auth::user()->companies;
        return view('auth.select-company', compact('companies'));
    }

    public function set(Request $request)
    {
        $company_id = $request->company_id;
        $selected_company = Company::find($company_id);        
        session(['company' => $selected_company]);
        
        return redirect()->route('welcome');
    }
}
