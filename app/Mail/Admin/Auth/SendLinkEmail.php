<?php

namespace App\Mail\Admin\Auth;

use App\Jobs\QueuesNames;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendLinkEmail extends Mailable
{
    use Queueable;
    use SerializesModels;

    /**
     * @var string
     */
    public string $email;

    /**
     * @var string|string|null
     */
    public $subject;

    /**
     * @var string
     */
    public string $linkResetPassword;

    /**
     * Create a new message instance.
     *
     * @param string $email
     * @param string $token
     */
    public function __construct(string $email, string $token)
    {
        $this->email             = $email;
        $this->subject           = __('email_subjects.forgot_password');
        $this->linkResetPassword = config('app.domain_admin') . '/admin/recovery_password?token=' . $token;
        $this->onQueue(QueuesNames::DEFAULT);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): self
    {
        return $this->view('emails.admin.auth.send_link_forgot_password')->with(
            [
                'email'             => $this->email,
                'linkResetPassword' => $this->linkResetPassword,
            ]
        )->subject($this->subject);
    }
}
