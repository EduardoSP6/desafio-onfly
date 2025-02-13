<?php

namespace Application\UseCases\TravelOrder\Create;

use Application\Interfaces\TravelOrderRepository;
use DateTimeImmutable;
use Domain\Core\Entity\TravelOrder;
use Domain\Core\Entity\User;
use Domain\Core\Enum\TravelOrderStatus;
use Domain\Shared\ValueObject\OrderId;
use Domain\Shared\ValueObject\Uuid;
use Illuminate\Support\Facades\Auth;

class CreateTravelOrderUseCase
{
    private TravelOrderRepository $travelOrderRepository;

    public function __construct(TravelOrderRepository $travelOrderRepository)
    {
        $this->travelOrderRepository = $travelOrderRepository;
    }

    public function execute(CreateTravelOrderInputDto $inputDto): void
    {
        $user = $this->getLoggedUser();

        $travelOrder = new TravelOrder(
            uuid: new Uuid(),
            orderId: new OrderId(),
            user: $user,
            destination: $inputDto->destination,
            departureDate: $inputDto->departureDate,
            returnDate: $inputDto->returnDate,
            status: TravelOrderStatus::REQUESTED,
            createdAt: new DateTimeImmutable()
        );

        $this->travelOrderRepository->create($travelOrder);
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
