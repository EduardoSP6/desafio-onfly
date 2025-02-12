<?php

namespace Application\UseCase\Auth\Login;

final class LoginInputDto
{
    public function __construct(
        public readonly string $email,
        public readonly string $password,
    )
    {
    }
}
