<?php

namespace Tests\Unit;

use Application\Exception\InvalidTravelOrderStatusException;
use Application\Exception\OperationNotPermittedException;
use DateInterval;
use DateTimeImmutable;
use Domain\Core\Entity\TravelOrder;
use Domain\Core\Entity\User;
use Domain\Core\Enum\TravelOrderStatus;
use Domain\Core\Factory\TravelOrderFactory;
use Domain\Core\Factory\UserFactory;
use Domain\Shared\ValueObject\OrderId;
use Domain\Shared\ValueObject\Uuid;
use DomainException;
use Tests\TestCase;

class TravelOrderTest extends TestCase
{
    public function test_it_should_fail_to_create_a_travel_order_with_return_date_less_than_departure_date()
    {
        $user = UserFactory::createOne();
        $departureDate = new DateTimeImmutable();
        $returnDate = $departureDate->sub(new DateInterval("P1D"));

        $this->assertInstanceOf(User::class, $user);
        $this->assertTrue($returnDate < $departureDate);
        $this->expectException(DomainException::class);

        $travelOrder = new TravelOrder(
            uuid: new Uuid(),
            orderId: new OrderId(),
            user: $user,
            destination: "São Paulo, Brasil",
            departureDate: $departureDate,
            returnDate: $returnDate,
            status: TravelOrderStatus::REQUESTED,
            createdAt: new DateTimeImmutable()
        );

        $this->assertNull($travelOrder);
    }

    public function test_it_should_create_a_travel_order_successfully()
    {
        $travelOrder = TravelOrderFactory::createOne();

        $this->assertInstanceOf(TravelOrder::class, $travelOrder);
    }

    public function test_it_should_fail_to_change_travel_order_status_by_requester_user()
    {
        $this->expectException(OperationNotPermittedException::class);

        $travelOrder = TravelOrderFactory::createOne();

        $this->assertInstanceOf(TravelOrder::class, $travelOrder);

        $loggedUser = $travelOrder->getUser();

        $travelOrder->changeStatus(TravelOrderStatus::APPROVED, $loggedUser);
    }

    public function test_it_should_fail_to_change_travel_order_status_when_it_is_approved_or_cancelled()
    {
        $this->expectException(InvalidTravelOrderStatusException::class);

        $travelOrder = TravelOrderFactory::createOne();
        $loggedUser = UserFactory::createOne();

        $travelOrder->changeStatus(TravelOrderStatus::APPROVED, $loggedUser);

        $travelOrder->changeStatus(TravelOrderStatus::CANCELLED, $loggedUser);
    }
}
