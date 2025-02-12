<?php

namespace Application\UseCases\Auth\Login;

final class LoginInputDto
{
    public function __construct(
        public readonly string $email,
        public readonly string $password,
    )
    {
    }
}
