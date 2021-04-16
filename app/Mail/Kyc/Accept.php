<?php

namespace App\Mail\Kyc;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Accept extends Mailable
{
    use Queueable, SerializesModels;

    public $subject;

    public string $name;

    /**
     * Create a new message instance.
     *
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->subject = __('email_subjects.kyc_accept');
        $this->name    = $name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.users.kyc.accept')->with(
            [
                'userName'   => $this->name,
            ]
        )->subject($this->subject);
    }
}
