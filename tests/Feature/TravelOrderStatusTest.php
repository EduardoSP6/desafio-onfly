<?php

namespace Tests\Feature;

use Domain\Core\Enum\TravelOrderStatus;
use Infrastructure\Persistence\Models\TravelOrder;
use Infrastructure\Persistence\Models\User;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class TravelOrderStatusTest extends TestCase
{
    private User $user;
    private string $token;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::query()->firstWhere('is_admin', '=', true);
        $this->token = JWTAuth::fromUser($this->user);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->user, $this->token);
    }

    public function test_it_should_approve_a_travel_order()
    {
        /** @var TravelOrder $travelOrder */
        $travelOrder = TravelOrder::query()->first();

        $this->assertNotNull($travelOrder);

        $response = $this
            ->withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $this->token
            ])
            ->patch("/api/v1/travel-orders/$travelOrder->order_id/approve");

        $response->assertOk();
    }

    public function test_it_should_cancel_a_travel_order()
    {
        /** @var TravelOrder $travelOrder */
        $travelOrder = TravelOrder::query()
            ->firstWhere('status', '=', TravelOrderStatus::REQUESTED->value);

        $this->assertNotNull($travelOrder);

        $response = $this
            ->withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $this->token
            ])
            ->patch("/api/v1/travel-orders/$travelOrder->order_id/cancel");

        $response->assertOk();
    }
}
