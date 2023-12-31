<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendStudentEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $noi_dung_gui;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($noi_dung_gui)
    {
        $this->noi_dung_gui = $noi_dung_gui;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.send-student-email');
    }
}
