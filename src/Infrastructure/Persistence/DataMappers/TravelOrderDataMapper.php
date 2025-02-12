<?php

namespace Infrastructure\Persistence\DataMappers;

use Domain\Core\Entity\TravelOrder;
use Domain\Core\Entity\User;
use Domain\Core\Enum\TravelOrderStatus;
use Domain\Shared\Entity\BaseEntity;
use Domain\Shared\ValueObject\OrderId;
use Domain\Shared\ValueObject\Uuid;
use Illuminate\Database\Eloquent\Model;
use Infrastructure\Persistence\Models\TravelOrder as TravelOrderModel;
use Infrastructure\Persistence\Models\User as UserModel;

class TravelOrderDataMapper extends BaseDataMapper
{

    /**
     * @param TravelOrderModel $model
     * @return TravelOrder
     */
    public function toDomain(Model $model): TravelOrder
    {
        $user = new User(
            uuid: new Uuid($model->user->uuid),
            name: $model->user->name,
            email: $model->user->email,
            isAdmin: $model->user->is_admin,
            createdAt: $model->user->created_at,
            updatedAt: $model->user->updated_at,
        );

        return new TravelOrder(
            uuid: new Uuid($model->uuid),
            orderId: new OrderId($model->order_id),
            user: $user,
            destination: $model->destination,
            departureDate: $model->departure_date,
            returnDate: $model->return_date,
            status: TravelOrderStatus::from($model->status),
            createdAt: $model->created_at,
            updatedAt: $model->updated_at
        );
    }

    /**
     * @param TravelOrder $entity
     * @return TravelOrderModel
     */
    public function toPersistence(BaseEntity $entity): TravelOrderModel
    {
        $userModel = UserModel::query()
            ->firstWhere('uuid', '=', $entity->getUser()->getUuid()->value());

        $travelOrderModel = new TravelOrderModel();
        $travelOrderModel->uuid = $entity->getUuid()->value();
        $travelOrderModel->order_id = $entity->getOrderId()->value();
        $travelOrderModel->user_id = $userModel->id;
        $travelOrderModel->destination = $entity->getDestination();
        $travelOrderModel->departure_date = $entity->getDepartureDate();
        $travelOrderModel->return_date = $entity->getReturnDate();
        $travelOrderModel->status = $entity->getStatus()->value;

        return $travelOrderModel;
    }
}
