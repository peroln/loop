<?php

declare(strict_types=1);

namespace App\Exceptions\Http;

use Illuminate\Http\Response;

/**
 * Class AccessDenyException
 * @package App\Exceptions
 */
class AccessDenyException extends \Exception
{
    protected $code = Response::HTTP_FORBIDDEN;

    protected $message = "User does not have permissions";
}
