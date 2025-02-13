<?php

namespace Application\Exception;

use RuntimeException;

class UnauthorizedException extends RuntimeException
{
    protected $code = 401;
    protected $message = "Credenciais incorretas";
}
