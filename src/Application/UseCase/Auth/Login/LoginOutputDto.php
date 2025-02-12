<?php

namespace Application\UseCase\Auth\Login;

final class LoginOutputDto
{
    public function __construct(
        public readonly string $accessToken,
    )
    {
    }
}
