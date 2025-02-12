<?php

namespace Tests\Feature;

use Infrastructure\Persistence\Models\User;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthTest extends TestCase
{
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::query()->first();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->user);
    }

    public function test_it_should_login_user_successfully()
    {
        $response = $this
            ->withHeaders([
                'Accept' => 'application/json',
            ])
            ->post("/api/v1/login", [
                'email' => $this->user->email,
                'password' => "password",
            ]);

        $response->assertOk()
            ->assertJsonStructure([
                'access_token',
                'token_type',
                'expires_in',
            ]);
    }

    public function test_it_should_fail_to_login_with_wrong_credentials()
    {
        $response = $this
            ->withHeaders([
                'Accept' => 'application/json',
            ])
            ->post("/api/v1/login", [
                'email' => 'xyz123543@gmail.com',
                'password' => "Wi5j3ofwe",
            ]);

        $response->assertUnauthorized();
    }

    public function test_it_should_logout_successfully()
    {
        $token = JWTAuth::fromUser($this->user);

        $response = $this
            ->withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $token
            ])
            ->post("/api/v1/logout");

        $response->assertOk();
    }
}
