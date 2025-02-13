<?php

namespace Application\UseCases\TravelOrder\Approve;

use Application\Interfaces\TravelOrderRepository;
use Domain\Core\Entity\TravelOrder;
use Domain\Core\Entity\User;
use Domain\Core\Enum\TravelOrderStatus;
use Domain\Shared\ValueObject\OrderId;
use Domain\Shared\ValueObject\Uuid;
use DomainException;
use Illuminate\Support\Facades\Auth;

class ApproveTravelOrderUseCase
{
    private TravelOrderRepository $travelOrderRepository;

    public function __construct(TravelOrderRepository $travelOrderRepository)
    {
        $this->travelOrderRepository = $travelOrderRepository;
    }

    public function execute(string $orderId): void
    {
        $travelOrder = $this->findTravelOrder($orderId);
        $loggedUser = $this->getLoggedUser();

        $travelOrder->changeStatus(TravelOrderStatus::APPROVED, $loggedUser);

        $this->travelOrderRepository->updateStatus($travelOrder);
    }

    protected function findTravelOrder(string $orderId): TravelOrder
    {
        $orderIdValueObject = new OrderId($orderId);

        $travelOrder = $this->travelOrderRepository->findByOrderId($orderIdValueObject);

        throw_if(!$travelOrder, new DomainException("Pedido de viagem não encontrado"));

        return $travelOrder;
    }

    protected function getLoggedUser(): User
    {
        /** @var \Infrastructure\Persistence\Models\User $userModel */
        $userModel = Auth::user();

        return new User(
            uuid: new Uuid($userModel->uuid),
            name: $userModel->name,
            email: $userModel->email,
            isAdmin: $userModel->is_admin,
            createdAt: $userModel->created_at,
            updatedAt: $userModel->updated_at
        );
    }
}
