<?php

namespace App\Mail\Admin\User;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Unblocked extends Mailable
{
    use Queueable, SerializesModels;

    protected string $firstname;

    /**
     * Create a new message instance.
     *
     * @param $firstname
     */
    public function __construct($firstname)
    {
        $this->firstname = $firstname;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.admin.users.unblocked')->with(
            [
                'firstname' => $this->firstname,
            ]
        );
    }
}
