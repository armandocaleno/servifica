<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VoucherMail extends Mailable
{
    use Queueable, SerializesModels;
    public $transaction, $company, $path;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($transaction, $company)
    {
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
        $path = 'Pago_' . $this->transaction->number . '.pdf';
        return $this->view('mails.voucher')->subject('Gracias por su pago!')->attachFromStorage($path);
    }
}
