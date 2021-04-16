<?php

namespace App\Mail\Admin;

use App\Models\Admin;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminInvited extends Mailable
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
    public string $firstname;

    public string $password;

    public string $email;

    /**
     * Registration constructor.
     *
     * @param \App\Models\Admin $admin
     * @param string            $password
     * @param string            $userType
     */
    public function __construct(Admin $admin, string $password, string $userType = "user")
    {
        $this->subject   = __('email_subjects.new_admin_invited');
        $this->firstname = $admin->first_name;
        $this->password  = $password;
        $this->email     = $admin->email;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.admin.invite.invited')->subject($this->subject);
    }
}
