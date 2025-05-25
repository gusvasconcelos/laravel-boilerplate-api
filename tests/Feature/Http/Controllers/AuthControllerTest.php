<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected string $url = '/api/v1/auth';

    public function test_login_with_successful(): void
    {
        $user = User::factory()->create();

        $form = [
            'email' => $user->email,
            'password' => 'password',
        ];

        $response = $this->postJson("$this->url/login", $form);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'access_token',
                'token_type',
                'expires_in',
            ]);
    }

    public function test_login_with_invalid_credentials(): void
    {
        $faker = \Faker\Factory::create(\config('app.locale'));

        $form = [
            'email' => $faker->email(),
            'password' => 'password',
        ];

        $response = $this->postJson("$this->url/login", $form);

        $response
            ->assertStatus(422)
            ->assertJson([
                'message' => 'As credenciais estão inválidas.',
                'status' => 422,
                'code' => 'INVALID_CREDENTIALS'
            ]);
    }

    public function test_login_with_validation_errors(): void
    {
        $faker = \Faker\Factory::create(\config('app.locale'));

        $form = [
            'email' => $faker->word(),
        ];

        $response = $this->postJson("$this->url/login", $form);

        $response
            ->assertStatus(422)
            ->assertJson([
                'message' => 'Erro na validação de campo.',
                'status' => 422,
                'code' => 'VALIDATION',
                'details' => [
                    'email' => [
                        'O campo Email deve ser um endereço de e-mail válido.'
                    ],
                    'password' => [
                        'O campo Senha é obrigatório.'
                    ]
                ]
            ]);
    }

    public function test_me_with_successful(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAsUser($user)->getJson("$this->url/me");

        $response
            ->assertStatus(200)
            ->assertJson([
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ]);
    }

    public function test_me_not_authenticated(): void
    {
        $response = $this->getJson("$this->url/me");

        $response
            ->assertStatus(401)
            ->assertJson([
                'message' => 'Não autenticado.',
                'status' => 401,
                'code' => 'UNAUTHORIZED',
                'details' => 'Para usar este recurso deve estar logado.'
            ]);
    }

    public function test_logout_with_successful(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAsUser($user)->postJson("$this->url/logout");

        $response
            ->assertStatus(200)
            ->assertJson([
                'message' => 'Logout feito com sucesso.'
            ]);
    }

    public function test_logout_without_token(): void
    {
        $response = $this->postJson("$this->url/logout");

        $response
            ->assertStatus(401)
            ->assertJson([
                'message' => 'O token não pôde ser analisado a partir da solicitação.',
                'status' => 401,
                'code' => 'AUTH_JWT_ERROR'
            ]);
    }

    public function test_refresh_with_successful(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAsUser($user)->postJson("$this->url/refresh");

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'access_token',
                'token_type',
                'expires_in',
            ]);
    }

    public function test_refresh_not_authenticated(): void
    {
        $response = $this->postJson("$this->url/refresh");

        $response
            ->assertStatus(401)
            ->assertJson([
                'message' => 'O token não pôde ser analisado a partir da solicitação.',
                'status' => 401,
                'code' => 'AUTH_JWT_ERROR'
            ]);
    }
}
