<?php

namespace App\Exceptions\Application;

use Exception;

class ApplicationException extends Exception
{
    protected $message = "Something went wrong!";
}
