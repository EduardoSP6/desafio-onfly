<?php

namespace Tests\Unit;

use App\Notifications\TravelOrderStatusUpdated;
use Domain\Core\Entity\TravelOrder;
use Domain\Core\Entity\User;
use Domain\Core\Enum\TravelOrderStatus;
use Domain\Shared\ValueObject\OrderId;
use Domain\Shared\ValueObject\Uuid;
use Illuminate\Support\Facades\Notification;
use Infrastructure\Persistence\Models\TravelOrder as TravelOrderModel;
use Infrastructure\Services\UserNotificationService;
use Tests\TestCase;

class UserNotificationServiceTest extends TestCase
{
    public function test_it_should_send_a_order_travel_status_change_notification()
    {
        /** @var TravelOrderModel $travelOrderModel */
        $travelOrderModel = TravelOrderModel::query()->first();

        $this->assertNotNull($travelOrderModel);

        $user = new User(
            uuid: new Uuid($travelOrderModel->user->uuid),
            name: $travelOrderModel->user->name,
            email: $travelOrderModel->user->email,
            isAdmin: $travelOrderModel->user->is_admin,
            createdAt: $travelOrderModel->user->created_at
        );

        $travelOrder = new TravelOrder(
            uuid: new Uuid($travelOrderModel->uuid),
            orderId: new OrderId($travelOrderModel->order_id),
            user: $user,
            destination: $travelOrderModel->destination,
            departureDate: $travelOrderModel->departure_date,
            returnDate: $travelOrderModel->return_date,
            status: TravelOrderStatus::from($travelOrderModel->status),
            createdAt: $travelOrderModel->created_at
        );

        Notification::fake();

        UserNotificationService::sendTravelOrderStatusUpdatedNotification($travelOrder);

        Notification::assertSentTo(
            $travelOrderModel->user,
            TravelOrderStatusUpdated::class
        );

        Notification::assertCount(1);
    }
}
