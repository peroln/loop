<?php

declare(strict_types=1);

namespace App\Exceptions\Application;

use Illuminate\Http\Response;

class RepositoryException extends \Exception
{
    protected $code = Response::HTTP_INTERNAL_SERVER_ERROR;

    /**
     * RepositoryException constructor.
     *
     * @param string $message
     */
    public function __construct($message)
    {
        parent::__construct($message);
    }
}
