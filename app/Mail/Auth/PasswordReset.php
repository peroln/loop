<?php

namespace App\Mail\Auth;

use App\Jobs\QueuesNames;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Class PasswordReset
 *
 * @package App\Mail\Auth
 */
class PasswordReset extends Mailable
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
    private string $resetUrl;

    /**
     * @var string
     */
    public string $username;

    /**
     * PasswordReset constructor.
     *
     * @param string $username
     * @param string $token
     * @param string $userType
     */
    public function __construct(string $username, string $token, string $userType = 'user')
    {
        $this->subject  = __('email_subjects.forgot_password');
        $this->username = $username;
        $this->resetUrl = config('app.domain') . "/recovery-pass?" . $token;
        $this->onQueue(QueuesNames::JOBS);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): self
    {

        return $this->view('emails.users.auth.forgot_password')->with(
            [
                'name'     => $this->username,
                'resetUrl' => $this->resetUrl,
            ]
        )->subject($this->subject);
    }
}
