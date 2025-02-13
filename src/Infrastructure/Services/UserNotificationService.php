<?php

namespace Infrastructure\Services;

use App\Notifications\TravelOrderStatusUpdated;
use Domain\Core\Entity\TravelOrder;
use Illuminate\Support\Facades\Notification;
use Infrastructure\Persistence\Models\User as UserModel;

class UserNotificationService
{
    public static function sendTravelOrderStatusUpdatedNotification(TravelOrder $travelOrder): void
    {
        $userModel = UserModel::query()
            ->firstWhere('uuid', '=', $travelOrder->getUser()->getUuid()->value());

        Notification::sendNow($userModel, new TravelOrderStatusUpdated($travelOrder));
    }
}
