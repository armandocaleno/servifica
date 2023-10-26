<?php

namespace App\Http\Controllers;


use App\Models\Accounting;
use Illuminate\Http\Request;

class AccountingController extends Controller
{

    public $global_level = 4;

    public function index()
    {
        return view('accountings.index');
    }

    public function create()
    {   
        $accounting = new Accounting();
        $level = $this->global_level;
       
        return view('accountings.create', compact('accounting', 'level'));
    }

    public function edit(Accounting $accounting)
    {   
        $level = $this->global_level;

        return view('accountings.create', compact('accounting', 'level'));
    }
}
