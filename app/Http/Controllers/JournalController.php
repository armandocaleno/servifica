<?php

namespace App\Http\Controllers;

use PDF;
use Carbon\Carbon;
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

    public function generatevoucher($id)
    {
        if ($id != null) {
        
            $journal = Journal::find($id); //obtiene el asiento contable 
    
            if ($journal != null) {
            
                $journal->date = Carbon::parse($journal->date)->format('d/m/Y'); //formatea la fecha       
               
                //genera el pdf con el contenido del asiento contable
                $pdf = PDF::loadView('journals.voucher', ['journal' => $journal, 'company' => session('company')]);

                //muestra el pdf    
                return $pdf->stream();
            }else {
                $this->info('Error al obtener los datos.');
            }
            
        }else {
            $this->info('Error al obtener los datos.');
        }
    }
}
