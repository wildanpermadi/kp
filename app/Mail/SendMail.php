<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendMail extends Mailable
{
    use Queueable, SerializesModels;

    private $nama;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($nama, $pdf_location)
    {
        $this->nama = $nama;
        $this->pdf = $pdf_location;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Ijazah berhasil dilegalisir!')
                    ->from('wildanpermadi2@gmail.com')
                    ->view('email.legalisir')
                    ->with(
                    [
                        'nama' => $this->nama,
                        'pdf' => $this->pdf
                    ])
                    ->attach($this->pdf, [
                        'as' => 'ijazah-legalisir.pdf',
                        'mime' => 'application/pdf'
                    ]);
    }
}
