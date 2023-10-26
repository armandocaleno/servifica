<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;


class ExpenseController extends Controller
{
    public function index()
    {
        return view('expenses.index');
    }

    public function create()
    {
        $expense = new Expense();
        return view('expenses.create', compact('expense'));
    }

    public function edit(Expense $expense)
    {
        // dd($expense);
        return view('expenses.create', compact('expense'));
    }

}
