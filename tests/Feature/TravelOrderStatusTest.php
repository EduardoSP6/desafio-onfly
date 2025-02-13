<?php

namespace Tests\Feature;

use Domain\Core\Enum\TravelOrderStatus;
use Infrastructure\Persistence\Models\TravelOrder;
use Infrastructure\Persistence\Models\User;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class TravelOrderStatusTest extends TestCase
{
    public function test_it_should_approve_a_travel_order()
    {
        $user = User::query()->firstWhere('is_admin', '=', true);
        $token = JWTAuth::fromUser($user);

        /** @var TravelOrder $travelOrder */
        $travelOrder = TravelOrder::query()->first();

        $this->assertNotNull($travelOrder);

        $response = $this
            ->withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $token
            ])
            ->patch("/api/v1/travel-orders/$travelOrder->order_id/approve");

        $response->assertOk()->assertJson([
            "message" => "Pedido aprovado com sucesso"
        ]);
    }

    public function test_it_should_fail_to_approve_a_travel_order_with_not_admin_user()
    {
        $user = User::query()->firstWhere('is_admin', '=', false);
        $token = JWTAuth::fromUser($user);

        /** @var TravelOrder $travelOrder */
        $travelOrder = TravelOrder::query()->first();

        $this->assertNotNull($travelOrder);

        $response = $this
            ->withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $token
            ])
            ->patch("/api/v1/travel-orders/$travelOrder->order_id/approve");

        $response->assertForbidden()->assertJson([
            "message" => "Você não possui permissão para alterar status do pedido."
        ]);
    }

    public function test_it_should_fail_to_approve_a_already_approved_travel_order()
    {
        $user = User::query()->firstWhere('is_admin', '=', true);
        $token = JWTAuth::fromUser($user);

        /** @var TravelOrder $travelOrder */
        $travelOrder = TravelOrder::query()
            ->firstWhere('status', '=', TravelOrderStatus::APPROVED);

        $this->assertNotNull($travelOrder);

        $response = $this
            ->withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $token
            ])
            ->patch("/api/v1/travel-orders/$travelOrder->order_id/approve");

        $response->assertBadRequest();
    }

    public function test_it_should_cancel_a_travel_order()
    {
        $user = User::query()->firstWhere('is_admin', '=', true);
        $token = JWTAuth::fromUser($user);

        /** @var TravelOrder $travelOrder */
        $travelOrder = TravelOrder::query()
            ->firstWhere('status', '=', TravelOrderStatus::REQUESTED->value);

        $this->assertNotNull($travelOrder);

        $response = $this
            ->withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $token
            ])
            ->patch("/api/v1/travel-orders/$travelOrder->order_id/cancel");

        $response->assertOk()->assertJson([
            "message" => "Pedido cancelado com sucesso"
        ]);
    }

    public function test_it_should_fail_to_cancel_a_travel_order_with_not_admin_user()
    {
        $user = User::query()->firstWhere('is_admin', '=', false);
        $token = JWTAuth::fromUser($user);

        /** @var TravelOrder $travelOrder */
        $travelOrder = TravelOrder::query()->first();

        $this->assertNotNull($travelOrder);

        $response = $this
            ->withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $token
            ])
            ->patch("/api/v1/travel-orders/$travelOrder->order_id/cancel");

        $response->assertForbidden()->assertJson([
            "message" => "Você não possui permissão para alterar status do pedido."
        ]);
    }

    public function test_it_should_fail_to_cancel_a_already_cancelled_travel_order()
    {
        $user = User::query()->firstWhere('is_admin', '=', true);
        $token = JWTAuth::fromUser($user);

        /** @var TravelOrder $travelOrder */
        $travelOrder = TravelOrder::query()
            ->firstWhere('status', '=', TravelOrderStatus::CANCELLED);

        $this->assertNotNull($travelOrder);

        $response = $this
            ->withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $token
            ])
            ->patch("/api/v1/travel-orders/$travelOrder->order_id/cancel");

        $response->assertBadRequest();
    }
}
