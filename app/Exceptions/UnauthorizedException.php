<?php

namespace App\Exceptions;

class UnauthorizedException extends HttpException
{
    public function __construct(
        string $message,
        string $errorCode = 'UNAUTHORIZED',
        string|array $details = 'Para usar este recurso deve estar logado.'
    ) {
        parent::__construct($message, 401, $errorCode, $details);
    }
}
