<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Transaksi;

class VirtualAccountEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $transaksi;

    public function __construct(Transaksi $transaksi)
    {
        $this->transaksi = $transaksi;
    }

    public function build()
    {
        return $this->subject('Virtual Account Pembayaran Anda')
                    ->view('emails.virtual_account')
                    ->with(['transaksi' => $this->transaksi]);
    }
}
