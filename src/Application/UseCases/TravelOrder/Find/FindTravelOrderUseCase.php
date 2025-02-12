<?php

namespace Application\UseCases\TravelOrder\Find;

use Application\Interfaces\TravelOrderRepository;
use Domain\Core\Entity\TravelOrder;
use Domain\Shared\ValueObject\OrderId;

class FindTravelOrderUseCase
{
    private TravelOrderRepository $travelOrderRepository;

    public function __construct(TravelOrderRepository $travelOrderRepository)
    {
        $this->travelOrderRepository = $travelOrderRepository;
    }

    public function execute(string $orderId): ?TravelOrder
    {
        $orderIdValueObject = new OrderId($orderId);

        return $this->travelOrderRepository->findByOrderId($orderIdValueObject);
    }
}
