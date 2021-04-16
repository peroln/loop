<?php

namespace App\Mail\Auth;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Disabled2FA extends Mailable
{
    use Queueable;
    use SerializesModels;

    public $subject;

    public string $username;

    /**
     * Disabled2FA constructor.
     *
     * @param string $username
     */
    public function __construct(string $username)
    {
        $this->subject = __('email_subjects.disabled_2fa');
        $this->username = $username;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.users.auth.disabled_2fa')->subject($this->subject);
    }
}
