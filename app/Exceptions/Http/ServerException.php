<?php

namespace App\Exceptions\Http;

use App\Exceptions\ErrorMessages;
use Exception;
use Illuminate\Http\Response;

class ServerException extends Exception
{
    protected $code = Response::HTTP_INTERNAL_SERVER_ERROR;

    protected $message = ErrorMessages::SOMETHING_WENT_WRONG;
}
