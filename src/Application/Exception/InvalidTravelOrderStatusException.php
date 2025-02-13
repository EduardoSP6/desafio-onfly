<?php

namespace Application\Exception;

use DomainException;

class InvalidTravelOrderStatusException extends DomainException
{
    protected $message = "Status do pedido de viagem é inválido para esta operação";
}
