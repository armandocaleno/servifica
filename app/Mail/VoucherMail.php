<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VoucherMail extends Mailable
{
    use Queueable, SerializesModels;
    public $partner, $transaction, $company;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($partner, $transaction, $company)
    {
        $this->partner = $partner;
        $this->transaction = $transaction;
        $this->company = $company;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mails.voucher')->subject('Gracias por su pago!')->attachFromStorage('Comprobante.pdf');
    }
}
