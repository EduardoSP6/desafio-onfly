<?php

namespace Application\Interfaces;

use Domain\Core\Entity\TravelOrder;
use Domain\Shared\ValueObject\OrderId;

interface TravelOrderRepository
{
    public function create(TravelOrder $travelOrder): void;

    public function updateStatus(TravelOrder $travelOrder): void;

    public function findByOrderId(OrderId $orderId): ?TravelOrder;

    public function listAll(): array;
}
