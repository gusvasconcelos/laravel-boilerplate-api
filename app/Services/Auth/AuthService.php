<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Support\Collection;
use App\Exceptions\UnauthorizedException;
use App\Exceptions\UnprocessableEntityException;

class AuthService
{
    public function login(Collection $request): string
    {
        $token = auth('api')->attempt($request->toArray());

        if (! $token) {
            throw new UnprocessableEntityException(__('messages.auth.invalid_credentials'), 'INVALID_CREDENTIALS');
        }

        return $token;
    }

    public function me(): User
    {
        $user = auth('api')->user();

        if (! $user) {
            throw new UnauthorizedException(__('messages.auth.not_authenticated'));
        }

        return $user;
    }

    public function logout(): void
    {
        auth('api')->logout();
    }

    public function refresh(): string
    {
        $token = auth('api')->refresh();

        return $token;
    }
}
