<?php

namespace App\Exceptions\Model;

use Exception;
use Illuminate\Http\Response;

class NotCreatedException extends Exception
{
    protected $code = Response::HTTP_FORBIDDEN;

    protected $message = "Model not created";
}
