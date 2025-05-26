<?php

namespace App\Exceptions;

class UnauthorizedException extends HttpException
{
    public function __construct(
        string $message,
        string $errorCode = 'UNAUTHORIZED',
    ) {
        parent::__construct($message, 401, $errorCode);
    }
}
