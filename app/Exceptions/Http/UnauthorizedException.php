<?php

declare(strict_types=1);

namespace App\Exceptions\Http;

use Illuminate\Http\Response;

/**
 * Class UnauthorizedException
 * @package App\Exceptions
 */
class UnauthorizedException extends \Exception
{
    protected $code = Response::HTTP_UNAUTHORIZED;

    protected $message = "User can't authorized in system";
}
