<?php

namespace App\Exceptions\Application;

use App\Exceptions\Http\ServerException;
use Illuminate\Http\Response;

class FileUploadException extends ServerException
{
    protected $message = 'Error during file upload';

    protected $code = Response::HTTP_BAD_REQUEST;
}
