<?php

namespace Application\UseCases\TravelOrder\Create;

use DateTimeImmutable;

final class CreateTravelOrderInputDto
{
    public function __construct(
        public readonly string            $destination,
        public readonly DateTimeImmutable $departureDate,
        public readonly DateTimeImmutable $returnDate,
    )
    {
    }
}
