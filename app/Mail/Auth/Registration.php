<?php

namespace App\Mail\Auth;

use App\Jobs\QueuesNames;
use App\Models\EmailConfirmation;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Class Registration
 *
 * @package App\Mail\Auth
 */
class Registration extends Mailable
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
    public string $confirmationUrl;

    /**
     * @var string
     */
    public ?string $username;

    /**
     * Registration constructor.
     *
     * @param EmailConfirmation $emailConfirmation
     * @param                   $user
     * @param string            $userType
     */
    public function __construct(EmailConfirmation $emailConfirmation, $user, string $userType = "user")
    {
        $this->subject = __('email_subjects.welcome');
        $this->username = $user->user_name;
        $this->confirmationUrl = config('app.domain', 'http://localhost') . "/login?"
            . $emailConfirmation->token;
        $this->onQueue(QueuesNames::DEFAULT);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.users.auth.registration')->subject($this->subject);
    }
}
