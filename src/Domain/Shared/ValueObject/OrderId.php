<?php

namespace Domain\Shared\ValueObject;

class OrderId
{
    private readonly string $orderId;

    public function __construct(?string $orderId = null)
    {
        $this->orderId = $orderId ?? time() . rand(1000, 9999);
    }

    public function value(): string
    {
        return $this->orderId;
    }
}
