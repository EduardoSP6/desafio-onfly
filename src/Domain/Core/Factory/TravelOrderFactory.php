<?php

namespace Domain\Core\Factory;

use DateInterval;
use DateTimeImmutable;
use Domain\Core\Entity\TravelOrder;
use Domain\Core\Entity\User;
use Domain\Core\Enum\TravelOrderStatus;
use Domain\Shared\ValueObject\OrderId;
use Domain\Shared\ValueObject\Uuid;
use Faker\Factory as Faker;

class TravelOrderFactory
{
    public static function createOne(): TravelOrder
    {
        $faker = Faker::create();

        $user = new User(
            uuid: new Uuid(),
            name: $faker->name,
            email: $faker->email,
            isAdmin: false,
            createdAt: new DateTimeImmutable()
        );

        $departureDate = new DateTimeImmutable();
        $returnDate = $departureDate->add(new DateInterval("P2D"));

        return new TravelOrder(
            uuid: new Uuid(),
            orderId: new OrderId(),
            user: $user,
            destination: $faker->city . ", " . $faker->country,
            departureDate: $departureDate,
            returnDate: $returnDate,
            status: TravelOrderStatus::REQUESTED,
            createdAt: new DateTimeImmutable()
        );
    }
}
