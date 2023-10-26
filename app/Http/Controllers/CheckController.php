<?php

namespace App\Http\Controllers;

use App\Models\Check;
use Illuminate\Http\Request;

class CheckController extends Controller
{
    public function index()
    {
        return view('checks.index');
    }

    public function create()
    {
        $check = new Check();
        return view('checks.create', compact('check'));
    }

    public function income()
    {
        $check = new Check();
        return view('checks.income', compact('check'));
    }

    public function edit(Check $check)
    {
        return view('checks.create', compact('check'));
    }
    
}
