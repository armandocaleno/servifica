<?php

namespace App\Http\Controllers;

use App\Models\Journal;
use Illuminate\Http\Request;

class JournalController extends Controller
{
    public function index()
    {
        return view('journals.index');
    }

    public function create()
    {
        $journal = new Journal();
        return view('journals.create',  compact('journal'));
    }

    public function edit(Journal $journal)
    {
        return view('journals.create', compact('journal'));
    }
}
