<?php

namespace Dmn\CloudflareTurnstile;

use Dmn\Exceptions\Exception;

class NotHumanException extends Exception
{
    protected $httpStatusCode = 400;

    protected $code = 'not_human';

    public $message = 'NOT HUMAN!';
}
