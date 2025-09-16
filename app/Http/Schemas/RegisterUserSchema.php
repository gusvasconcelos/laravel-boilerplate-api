<?php

namespace App\Http\Schemas;

use OpenApi\Attributes\Schema;
use OpenApi\Attributes\Property;

#[Schema(
    schema: 'registerUser',
    description: 'Register user schema',
    type: 'object',
    required: ['name', 'email', 'password', 'password_confirmation'],
)]
class RegisterUserSchema
{
    #[Property(
        property: 'name',
        description: 'User name',
        type: 'string',
        example: 'John Doe'
    )]
    public string $name;

    #[Property(
        property: 'email',
        description: 'User email',
        type: 'string',
        format: 'email',
        example: 'john.doe@example.com'
    )]
    public string $email;

    #[Property(
        property: 'password',
        description: 'User password',
        type: 'string',
        example: 'password123',
        minLength: 8
    )]
    public string $password;

    #[Property(
        property: 'password_confirmation',
        description: 'User password confirmation',
        type: 'string',
        example: 'password123',
        minLength: 8
    )]
    public string $password_confirmation;
}
