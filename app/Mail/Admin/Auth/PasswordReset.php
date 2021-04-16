<?php

namespace App\Mail\Admin\Auth;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Class PasswordReset
 *
 * @package App\Mail\Admin\Auth
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
    private string $username;

    /**
     * @var string
     */
    private string $newPassword;

    public function __construct(string $username, string $newPassword)
    {
        $this->subject = __('email_subjects.forgot_password');
        $this->username = $username;
        $this->newPassword = $newPassword;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): self
    {
        return $this->view('emails.admin.auth.forgot_password')->with(
            [
                'username' => $this->username,
                'password' => $this->newPassword,
            ]
        )->subject($this->subject);
    }
}
