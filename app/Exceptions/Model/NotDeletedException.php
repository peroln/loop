<?php

namespace App\Exceptions\Model;

use Exception;
use Illuminate\Http\Response;

class NotDeletedException extends Exception
{
    protected $code = Response::HTTP_FORBIDDEN;

    protected $message = 'Model does not deleted';
}
