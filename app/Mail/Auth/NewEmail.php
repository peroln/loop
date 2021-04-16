<?php

namespace App\Mail\Auth;

use App\Jobs\QueuesNames;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewEmail extends Mailable
{
    use Queueable;

    use SerializesModels;

    public $subject;

    public string $confirmationUrl;

    /**
     * Registration constructor.
     *
     * @param string $token
     * @param string $userType
     */
    public function __construct(string $token, string $userType = 'carrier')
    {
        $this->subject = __('email_subjects.new_email');
        $this->confirmationUrl = config('app.domain', 'http://localhost') . "/token/${userType}?" . $token;
        $this->onQueue(QueuesNames::DEFAULT);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): self
    {
        return $this->view('emails.settings.new_email')->subject($this->subject);
    }
}
