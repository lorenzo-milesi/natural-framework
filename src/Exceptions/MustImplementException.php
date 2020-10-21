<?php

declare(strict_types = 1);

namespace NaturalFramework\Exceptions;

use Exception;
use Throwable;

class MustImplementException extends Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function __toString()
    {
        return __CLASS__ . ': ' . $this->message;
    }
}