<?php

namespace App\Exceptions\Application;

/**
 * Class ServiceException
 *
 * @package App\Exceptions
 */
class ServiceException extends \Exception
{
    protected $message = 'Service initializing error';
}
