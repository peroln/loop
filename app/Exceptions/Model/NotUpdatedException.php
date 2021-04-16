<?php

namespace App\Exceptions\Model;

use Exception;
use Illuminate\Http\Response;

class NotUpdatedException extends Exception
{
    protected $code = Response::HTTP_FORBIDDEN;

    protected $message = 'Model does not updated';
}
