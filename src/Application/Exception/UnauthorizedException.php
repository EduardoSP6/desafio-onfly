<?php

namespace Application\Exception;

class UnauthorizedException extends \Illuminate\Validation\UnauthorizedException
{
    protected $code = 401;
    protected $message = "Credenciais incorretas";
}
