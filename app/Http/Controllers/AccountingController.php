<?php

namespace App\Http\Controllers;


use App\Models\Accounting;
use Illuminate\Http\Request;

class AccountingController extends Controller
{

    public function index()
    {
        return view('accountings.index');
    }

    public function create()
    {   
        $accounting = new Accounting();
       
        return view('accountings.create', compact('accounting'));
    }

    public function edit(Accounting $accounting)
    {   
        return view('accountings.create', compact('accounting'));
    }
}
