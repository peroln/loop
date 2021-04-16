<?php

declare(strict_types=1);

namespace App\Exceptions\Http;

use Illuminate\Http\Response;

/**
 * Class BadRequestException
 * @package App\Exceptions
 */
class BadRequestException extends \Exception
{
    protected $code = Response::HTTP_BAD_REQUEST;

    protected $message = "Invalid credentials";
}
