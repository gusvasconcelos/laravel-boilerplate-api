<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    public function actingAsUser(User $user, $guard = null): self
    {
        $token = auth('api')->tokenById($user->id);

        return $this->withHeaders([
            'Authorization' => 'Bearer' . $token
        ]);
    }
}
