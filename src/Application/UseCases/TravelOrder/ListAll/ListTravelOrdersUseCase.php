<?php

namespace Application\UseCases\TravelOrder\ListAll;

use Application\Interfaces\TravelOrderRepository;

class ListTravelOrdersUseCase
{
    private TravelOrderRepository $travelOrderRepository;

    public function __construct(TravelOrderRepository $travelOrderRepository)
    {
        $this->travelOrderRepository = $travelOrderRepository;
    }

    public function execute(): array
    {
        return $this->travelOrderRepository->listAll();
    }
}
