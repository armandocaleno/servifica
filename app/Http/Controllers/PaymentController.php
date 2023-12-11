<?php

namespace App\Http\Controllers;

use PDF;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        return view('payments.index');
    }

    public function create()
    {
        $payment = new Payment();
        return view('payments.create', compact('payment'));
    }

    public function edit(Payment $payment)
    {
        return view('payments.create', compact('payment'));
    }

    public function generatevoucher($id)
    {
        if ($id != null) {
        
            $payment = Payment::find($id); //obtiene la transacción 
    
            if ($payment != null) {
            
                $payment->date = Carbon::parse($payment->date)->format('d/m/Y'); //formatea la fecha       
               
                //genera el pdf con el contenido de la transacción
                $pdf = PDF::loadView('payments.voucher', ['payment' => $payment, 'company' => session('company')]);

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
