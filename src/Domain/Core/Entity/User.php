<?php

namespace Domain\Core\Entity;

use DateTimeImmutable;
use Domain\Shared\Entity\BaseEntity;
use Domain\Shared\ValueObject\Uuid;
use DomainException;

class User extends BaseEntity
{
    private Uuid $uuid;
    private string $name;
    private string $email;
    private bool $isAdmin;
    private DateTimeImmutable $createdAt;
    private DateTimeImmutable|null $updatedAt;

    public function __construct(Uuid $uuid, string $name, string $email, bool $isAdmin, DateTimeImmutable $createdAt, ?DateTimeImmutable $updatedAt = null)
    {
        parent::__construct($uuid, $createdAt, $updatedAt);
        $this->uuid = $uuid;
        $this->name = $name;
        $this->email = $email;
        $this->isAdmin = $isAdmin;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;

        $this->validate();
    }

    protected function validate(): void
    {
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            throw new DomainException('E-mail inválido');
        }

        if (strlen($this->name) > 150) {
            throw new DomainException('Nome do usuário não pode ter mais de 150 caracteres');
        }
    }

    /**
     * @return Uuid
     */
    public function getUuid(): Uuid
    {
        return $this->uuid;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    public function changeName(string $name): void
    {
        if (strlen($this->name) > 150) {
            throw new DomainException('Nome do usuário não pode ter mais de 150 caracteres');
        }

        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->isAdmin;
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
