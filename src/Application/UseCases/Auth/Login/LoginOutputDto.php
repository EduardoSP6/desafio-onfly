<?php

namespace Application\UseCases\Auth\Login;

final class LoginOutputDto
{
    public function __construct(
        public readonly string $accessToken,
    )
    {
    }
}
