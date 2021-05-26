<?php

declare(strict_types=1);

namespace App\Exceptions\Ethereum;

use Illuminate\Http\Response;

/**
 * Class ContractException
 * @package App\Exceptions\Ethereum
 */
class ContractException extends \Exception
{
    protected $code = Response::HTTP_BAD_REQUEST;

    protected $message = "Something went wrong";
}

