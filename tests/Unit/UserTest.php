<?php

namespace Tests\Unit;

use DateTimeImmutable;
use Domain\Core\Entity\User;
use Domain\Shared\ValueObject\Uuid;
use DomainException;
use Faker\Factory as Faker;
use Tests\TestCase;

class UserTest extends TestCase
{
    public function test_it_should_create_an_user_successfully()
    {
        $faker = Faker::create();

        $name = $faker->name;
        $email = $faker->email;

        $user = new User(
            new Uuid(),
            $name,
            $email,
            false,
            new DateTimeImmutable()
        );

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals($name, $user->getName());
        $this->assertEquals($email, $user->getEmail());
    }

    public function test_it_should_fail_to_create_an_user_with_invalid_email()
    {
        $faker = Faker::create();

        $name = $faker->name;
        $email = $faker->userName;

        $this->expectException(DomainException::class);

        $user = new User(
            new Uuid(),
            $name,
            $email,
            false,
            new DateTimeImmutable()
        );

        $this->assertNull($user);
    }

    public function test_it_should_fail_to_create_an_user_with_invalid_name_length()
    {
        $faker = Faker::create();

        $name = $faker->realTextBetween(200, 300);
        $email = $faker->email;

        $this->expectException(DomainException::class);

        $user = new User(
            new Uuid(),
            $name,
            $email,
            false,
            new DateTimeImmutable()
        );

        $this->assertNull($user);
    }
}
