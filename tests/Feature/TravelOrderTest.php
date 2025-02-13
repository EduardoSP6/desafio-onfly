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

        $this->user = User::query()->firstWhere('email', '=', 'user1@onfly.com');
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

    public function test_it_should_find_a_travel_order_by_order_id_successfully()
    {
        $orderId = "17394012135715";

        $response = $this
            ->withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $this->token
            ])
            ->get("/api/v1/travel-orders/$orderId");

        $response->assertOk()->assertJsonStructure([
            'uuid',
            'orderId',
            'userName',
            'destination',
            'departureDate',
            'returnDate',
            'status',
            'statusDescription',
        ]);
    }

    public function test_it_should_list_all_travel_orders_successfully()
    {
        $response = $this
            ->withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $this->token
            ])
            ->get("/api/v1/travel-orders");

        $response->assertOk()->assertJsonStructure([
            '*' => [
                'uuid',
                'orderId',
                'userName',
                'destination',
                'departureDate',
                'returnDate',
                'status',
                'statusDescription',
            ]
        ]);
    }

    public function test_it_should_list_all_travel_orders_filtering_by_destination()
    {
        $response = $this
            ->withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $this->token
            ])
            ->get("/api/v1/travel-orders?filter[destination]=South");

        $response->assertOk()->assertJsonStructure([
            '*' => [
                'uuid',
                'orderId',
                'userName',
                'destination',
                'departureDate',
                'returnDate',
                'status',
                'statusDescription',
            ]
        ]);
    }

    public function test_it_should_list_all_travel_orders_filtering_by_status()
    {
        $response = $this
            ->withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $this->token
            ])
            ->get("/api/v1/travel-orders?filter[status]=requested");

        $response->assertOk()->assertJsonStructure([
            '*' => [
                'uuid',
                'orderId',
                'userName',
                'destination',
                'departureDate',
                'returnDate',
                'status',
                'statusDescription',
            ]
        ]);
    }

    public function test_it_should_list_all_travel_orders_filtering_by_travel_period()
    {
        $response = $this
            ->withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $this->token
            ])
            ->get("/api/v1/travel-orders?filter[period]=2025-02-12,2025-02-13");

        $response->assertOk()->assertJsonStructure([
            '*' => [
                'uuid',
                'orderId',
                'userName',
                'destination',
                'departureDate',
                'returnDate',
                'status',
                'statusDescription',
            ]
        ]);
    }

    public function test_it_should_list_all_travel_orders_filtering_by_travel_period_and_destination()
    {
        $response = $this
            ->withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $this->token
            ])
            ->get("/api/v1/travel-orders?filter[period]=2025-02-12,2025-02-13&filter[destination]=South");

        $response->assertOk()->assertJsonStructure([
            '*' => [
                'uuid',
                'orderId',
                'userName',
                'destination',
                'departureDate',
                'returnDate',
                'status',
                'statusDescription',
            ]
        ]);
    }
}
