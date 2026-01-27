<?php

namespace Cray\Laravel\Exceptions;

use Exception;

class CrayValidationException extends Exception
{
    public array $errors = [];

    public function __construct(string $message = "", int $code = 0, array $errors = [])
    {
        parent::__construct($message, $code);
        $this->errors = $errors;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
