<?php

declare(strict_types=1);

namespace App\Exceptions\Model;

use Illuminate\Http\Response;

/**
 * Class NotFoundException
 * @package App\Exceptions
 */
class NotFoundException extends \Exception
{
    protected $message = "model not found";

    protected $code = Response::HTTP_NOT_FOUND;
}
