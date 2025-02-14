<?php

namespace Application\Exception;

use DomainException;

class CancellationDeadlineApprovedTravelOrderExceededException extends DomainException
{
    protected $message = "Pedido aprovado não pode ser cancelado pois já ultrapassou o prazo limite para cancelamento.";
}
