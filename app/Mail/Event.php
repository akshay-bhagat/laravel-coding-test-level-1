<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Event extends Mailable
{
    use Queueable, SerializesModels;

    public $email;
    public $emailData;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($email,  $emailData)
    {
        $this->email = $email;
        $this->emailData = $emailData;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
                ->subject('New Event Notification')
                ->markdown('emails.event');
    }
}
