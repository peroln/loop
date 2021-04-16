<?php

namespace App\Traits;

use Illuminate\Support\Facades\Http;

trait ReCaptcha
{
    public function validateCaptcha($value)
    {
        $response = Http::asForm()->post(
            'https://www.google.com/recaptcha/api/siteverify',
            [
                'secret'   => config('app.google-recaptcha'),
                'response' => $value,
            ]
        );

        return $response->object()->success;
    }
}
