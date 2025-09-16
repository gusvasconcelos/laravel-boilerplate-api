<?php

namespace App\Services\User;

use Illuminate\Support\Collection;
use App\Models\User;
use Illuminate\Auth\Events\Verified;

class UserService
{
    public function register(Collection $data): User
    {
        $user = User::create($data->toArray());

        $this->sendEmailVerificationNotification($user);

        return $user;
    }

    public function sendEmailVerificationNotification(User $user): array
    {
        if ($user->hasVerifiedEmail()) {
            $message = __('messages.user.email_already_verified');

            return ['message' => $message];
        }

        $user->sendEmailVerificationNotification();

        $message = __('messages.user.email_verification_sent');

        return ['message' => $message];
    }

    public function verifyEmail(): array
    {
        /** @var User $user */
        $user = auth('api')->user();

        if ($user->hasVerifiedEmail()) {
            $message = __('messages.user.email_already_verified');

            return ['message' => $message];
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        $message = __('messages.user.email_verified');

        return ['message' => $message];
    }
}
