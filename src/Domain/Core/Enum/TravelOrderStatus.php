<?php

namespace Domain\Core\Enum;

enum TravelOrderStatus: string
{
    case REQUESTED = 'requested';
    case APPROVED = 'approved';
    case CANCELLED = 'cancelled';

    public function getDescription(): string
    {
        return match ($this) {
            self::REQUESTED => 'Solicitado',
            self::APPROVED => 'Aprovado',
            self::CANCELLED => 'Cancelado',
        };
    }
}
