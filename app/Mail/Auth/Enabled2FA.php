<?php

namespace App\Mail\Auth;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Class Enabled2FA
 *
 * @package App\Mail\Auth
 */
class Enabled2FA extends Mailable
{
    use Queueable;

    use SerializesModels;

    /**
     * @var array|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Translation\Translator|string|null
     */
    public $subject;

    /**
     * @var string
     */
    public string $username;

    /**
     * Enabled2FA constructor.
     *
     * @param string $username
     */
    public function __construct(string $username)
    {
        $this->subject = __('email_subjects.enabled_2fa');
        $this->username = $username;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.users.auth.enabled_2fa')->subject($this->subject);
    }
}
