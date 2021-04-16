<?php

namespace App\Mail\Kyc;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Reject extends Mailable
{
    use Queueable, SerializesModels;

    public $subject;

    public string $name;

    public string $reason;

    /**
     * Create a new message instance.
     *
     * @param $name
     * @param $message
     */
    public function __construct(string $name, string $message)
    {
        $this->subject = __('email_subjects.reject');
        $this->name    = $name;
        $this->reason  = $message;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.users.kyc.reject')->with(
            [
                'userName'   => $this->name,
                'reason' => $this->reason,
            ]
        )->subject($this->subject);
    }
}
