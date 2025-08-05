<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;
    public $data;
    public $title;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data, $title)
    {
        $this->data = $data;
        $this->title = $title;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->title)
        ->view(' frontend.mail_reset_password_notify')
        ->with('data', $this->data);
    }
}
