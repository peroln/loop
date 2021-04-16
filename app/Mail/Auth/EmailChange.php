<?php

namespace App\Mail\Auth;

use App\Jobs\QueuesNames;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Class EmailChange
 *
 * @package App\Mail\Auth
 */
class EmailChange extends Mailable
{
    use Queueable;
    use SerializesModels;

    /**
     * @var array|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Translation\Translator|string|null
     */
    public $subject;

    /**
     * @var string|null
     */
    public ?string $username;

    /**
     * Registration constructor.
     *
     * @param $username
     */
    public function __construct($username)
    {
        $this->subject = __('email_subjects.email_change');
        $this->username = $username;
        $this->onQueue(QueuesNames::EMAILS);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.users.settings.change_email')->subject($this->subject);
    }
}
