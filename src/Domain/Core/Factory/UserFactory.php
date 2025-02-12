<?php

namespace Domain\Core\Factory;

use DateTimeImmutable;
use Domain\Core\Entity\User;
use Domain\Shared\ValueObject\Uuid;
use Faker\Factory as Faker;

class UserFactory
{
    public static function createOne(bool $isAdmin = false): User
    {
        $faker = Faker::create();

        return new User(
            uuid: new Uuid(),
            name: $faker->name,
            email: $faker->email,
            isAdmin: $isAdmin,
            createdAt: new DateTimeImmutable()
        );
    }
}
