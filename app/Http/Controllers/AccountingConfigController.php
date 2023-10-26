<?php

namespace App\Http\Controllers;

use App\Models\AccountingConfig;
use Illuminate\Http\Request;

class AccountingConfigController extends Controller
{
    public function index()
    {
        return view('accountings-config.index');
    }
}
