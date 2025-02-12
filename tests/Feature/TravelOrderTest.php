<?php

namespace Tests\Feature;

use Faker\Factory as Faker;
use Infrastructure\Persistence\Models\User;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class TravelOrderTest extends TestCase
{
    private User $user;
    private string $token;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::query()->firstWhere('is_admin', '=', false);
        $this->token = JWTAuth::fromUser($this->user);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->user, $this->token);
    }

    public function test_it_should_create_a_travel_order_successfully()
    {
        $faker = Faker::create();

        $data = [
            'destination' => $faker->city . ", " . $faker->country,
            'departure_date' => now()->format('Y-m-d'),
            'return_date' => now()->addDay()->format('Y-m-d'),
        ];

        $response = $this
            ->withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $this->token
            ])
            ->post("/api/v1/travel-orders", $data);

        $response->assertCreated();
    }
}
