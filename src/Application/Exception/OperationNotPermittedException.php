<?php

namespace Application\Exception;

use RuntimeException;

class OperationNotPermittedException extends RuntimeException
{
    protected $code = 403;
    protected $message = "Operação não permitida";
}
