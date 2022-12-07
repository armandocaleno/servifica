<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompanyController extends Controller
{
    public function index()
    {
        return view('companies.index');
    }

    public function create()
    {
        $company = new Company();

        return view('companies.create', compact('company'));
    }

    public function store(Request $request)
    {
        return DB::transaction(function () use ($request) {
                        
            $this->validate($request, [
                'ruc' => 'required|min:10|max:13|unique:companies',
                'businessname' => 'required|min:3|max:100',
                'tradename' => 'nullable|min:3|max:100',
                'address' => 'nullable|max:255',
                'phone' => 'nullable|max:25',
                'logo' => 'nullable|file|max:1024|mimes:jpeg,jpg,png'
            ]);

            if (isset($request->logo)) {
                $filename = $request->logo->getClientOriginalName();     
                $path = "images/logo/".$request->ruc;       
                $request->logo->move(public_path($path), $filename);
            }                

            $company = Company::create([
                'ruc' => $request->ruc,
                'businessname' => $request->businessname,
                'tradename' => $request->tradename,
                'address' => $request->address,
                'phone' => $request->phone,
                'logo' => $filename
            ]);

            $company->users()->sync(auth()->user()->id);

            return redirect()->route('companies.index');
        }, 3);
    }

    public function edit(Company $company)
    {
        return view('companies.edit', compact('company'));
    }

    public function update(Request $request)
    {        
        return DB::transaction(function() use ($request){
            $this->validate($request, [
                'ruc' => 'required|min:10|max:13|unique:companies,ruc,'.$request->id,
                'businessname' => 'required|min:3|max:100',
                'tradename' => 'nullable|min:3|max:100',
                'address' => 'max:255',
                'phone' => 'max:25',
                'logo' => 'nullable|image|max:1024|mimes:jpeg,jpg,png'
            ]);
            
            $company = Company::find($request->id);
            $company->ruc = $request->ruc;
            $company->businessname = $request->businessname;
            $company->tradename = $request->tradename;
            $company->address = $request->address;
            $company->phone = $request->phone;
            

            if ($request->hasFile('logo')) {
                if ($company->logo != null) {
                    if (file_exists('images/logo/'.$company->ruc.'/'.$company->logo)) {
                        unlink('images/logo/'.$company->ruc.'/'.$company->logo);
                    }                    
                }

                $filename = $request->logo->getClientOriginalName();     
                $path = "images/logo/".$request->ruc;       
                $request->logo->move(public_path($path), $filename);
                $company->logo = $filename;
            }  

            $company->save();

            return redirect()->route('companies.index');
        },3);
    }
   
}
