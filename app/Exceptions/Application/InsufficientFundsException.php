<?php

namespace App\Exceptions\Application;

use Illuminate\Http\Response;

class InsufficientFundsException extends ApplicationException
{
    protected $message = 'insufficient funds';

    protected $code = Response::HTTP_BAD_REQUEST;
}
