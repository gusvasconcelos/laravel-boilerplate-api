<?php

namespace App\Exceptions;

class UnprocessableEntityException extends HttpException
{
    public function __construct(
        string $message,
        string $errorCode = 'UNPROCESSABLE_ENTITY',
        string|array $details = 'Verifique se os dados estão corretos.'
    ) {
        parent::__construct($message, 422, $errorCode, $details);
    }
}
