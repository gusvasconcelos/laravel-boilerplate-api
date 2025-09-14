<?php

namespace App\Exceptions;

class InternalServerErrorException extends HttpException
{
    public function __construct(
        string $message,
        string $errorCode = 'INTERNAL_SERVER_ERROR',
        string|array|null $details = null
    ) {
        parent::__construct($message, 500, $errorCode, $details);
    }
}
