<?php

namespace Application\UseCases\TravelOrder\Create;

use Application\Interfaces\TravelOrderRepository;
use Application\Interfaces\UserRepository;
use DateTimeImmutable;
use Domain\Core\Entity\TravelOrder;
use Domain\Core\Entity\User;
use Domain\Core\Enum\TravelOrderStatus;
use Domain\Shared\ValueObject\OrderId;
use Domain\Shared\ValueObject\Uuid;
use Illuminate\Support\Facades\Auth;
use RuntimeException;

class CreateTravelOrderUseCase
{
    private TravelOrderRepository $travelOrderRepository;
    private UserRepository $userRepository;

    public function __construct(TravelOrderRepository $travelOrderRepository, UserRepository $userRepository)
    {
        $this->travelOrderRepository = $travelOrderRepository;
        $this->userRepository = $userRepository;
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
        $user = $this->userRepository->findById((int)Auth::id());

        throw_if(!$user, new RuntimeException("Usuário não encontrado"));

        return $user;
    }
}
