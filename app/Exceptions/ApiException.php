<?php

namespace App\Exceptions;

use Exception;

class ApiException extends Exception
{
    protected $statusCode;
    protected $errorData = [];

    public function __construct(
        string $message = 'An error occurred',
        int $statusCode = 500,
        array $errorData = [],
        Exception $previous = null
    ) {
        parent::__construct($message, 0, $previous);
        $this->statusCode = $statusCode;
        $this->errorData = $errorData;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getErrorData(): array
    {
        return $this->errorData;
    }
}
