<?php

namespace Domain\Core\Entity;

use Application\Exception\InvalidTravelOrderStatusException;
use Application\Exception\OperationNotPermittedException;
use DateTimeImmutable;
use Domain\Core\Enum\TravelOrderStatus;
use Domain\Shared\Entity\BaseEntity;
use Domain\Shared\ValueObject\OrderId;
use Domain\Shared\ValueObject\Uuid;
use DomainException;

class TravelOrder extends BaseEntity
{
    private Uuid $uuid;
    private OrderId $orderId;
    private User $user;
    private string $destination;
    private DateTimeImmutable $departureDate;
    private DateTimeImmutable $returnDate;
    private TravelOrderStatus $status;
    private DateTimeImmutable $createdAt;
    private DateTimeImmutable|null $updatedAt;

    public function __construct(Uuid $uuid, OrderId $orderId, User $user, string $destination, DateTimeImmutable $departureDate, DateTimeImmutable $returnDate, TravelOrderStatus $status, DateTimeImmutable $createdAt, ?DateTimeImmutable $updatedAt = null)
    {
        parent::__construct($uuid, $createdAt, $updatedAt);
        $this->uuid = $uuid;
        $this->orderId = $orderId;
        $this->user = $user;
        $this->destination = $destination;
        $this->departureDate = $departureDate;
        $this->returnDate = $returnDate;
        $this->status = $status;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->validate();
    }

    protected function validate(): void
    {
        throw_if($this->returnDate < $this->departureDate,
            new DomainException('Data de retorno não pode ser inferior a data de partida.')
        );
    }

    /**
     * @return Uuid
     */
    public function getUuid(): Uuid
    {
        return $this->uuid;
    }

    /**
     * @return OrderId
     */
    public function getOrderId(): OrderId
    {
        return $this->orderId;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return string
     */
    public function getDestination(): string
    {
        return $this->destination;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getDepartureDate(): DateTimeImmutable
    {
        return $this->departureDate;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getReturnDate(): DateTimeImmutable
    {
        return $this->returnDate;
    }

    /**
     * @return TravelOrderStatus
     */
    public function getStatus(): TravelOrderStatus
    {
        return $this->status;
    }

    public function changeStatus(TravelOrderStatus $status, User $loggedUser): void
    {
        throw_if(!$loggedUser->isAdmin(),
            new OperationNotPermittedException(
                "Você não possui permissão para alterar status do pedido."
            )
        );

        throw_if(
            in_array($this->status, [TravelOrderStatus::APPROVED, TravelOrderStatus::CANCELLED]),
            new InvalidTravelOrderStatusException(
                "Pedido com status " . $this->status->getDescription() . " não pode ser alterado."
            )
        );

        $this->status = $status;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @return DateTimeImmutable|null
     */
    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }
}
