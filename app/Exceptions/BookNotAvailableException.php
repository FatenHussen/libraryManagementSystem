<?php

namespace App\Exceptions;

use Exception;

class BookNotAvailableException extends Exception
{
    public function __construct(string $message = "This Book is not available for borrowing", int $code = 0, \Throwable $previous = null){
        parent::__construct($message);
    }
}