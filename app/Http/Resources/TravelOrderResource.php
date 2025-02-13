<?php

namespace App\Http\Resources;

use Domain\Core\Entity\TravelOrder;
use Illuminate\Http\Resources\Json\JsonResource;

class TravelOrderResource extends JsonResource
{
    private TravelOrder $travelOrder;

    public function __construct(TravelOrder $travelOrder)
    {
        parent::__construct($this);

        $this->travelOrder = $travelOrder;
    }

    public function toArray($request): array
    {
        return [
            'uuid' => $this->travelOrder->getUuid()->value(),
            'orderId' => $this->travelOrder->getOrderId()->value(),
            'userName' => $this->travelOrder->getUser()->getName(),
            'destination' => $this->travelOrder->getDestination(),
            'departureDate' => $this->travelOrder->getDepartureDate()->format('d/m/Y'),
            'returnDate' => $this->travelOrder->getReturnDate()->format('d/m/Y'),
            'status' => $this->travelOrder->getStatus()->value,
            'statusDescription' => $this->travelOrder->getStatus()->getDescription(),
        ];
    }
}
