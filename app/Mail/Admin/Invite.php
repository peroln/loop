<?php

namespace App\Mail\Admin;

use App\Models\Admin\EmailConfirmation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Invite extends Mailable
{
    use Queueable;
    use SerializesModels;

    /**
     * @var array|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Translation\Translator|string|null
     */
    public $subject;

    public string $confirmationUrl;

    public string $firstname;

    public string $password;

    /**
     * Registration constructor.
     *
     * @param \App\Models\Admin\EmailConfirmation $emailConfirmation
     * @param                                     $user
     * @param string                              $userType
     */
    public function __construct(EmailConfirmation $emailConfirmation, string $password, $user, string $userType = "user")
    {
        $this->subject = __('email_subjects.welcome');
        $this->firstname = $user->first_name ;
        $this->confirmationUrl = config('app.domain_admin', 'http://localhost') . "/login?"
            . $emailConfirmation->token;
        $this->password = $password;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.admin.auth.invited')->subject($this->subject);
    }
}
