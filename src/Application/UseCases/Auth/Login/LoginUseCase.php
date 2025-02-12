<?php

namespace Application\UseCases\Auth\Login;

use Application\Exception\UnauthorizedException;
use Illuminate\Support\Facades\Auth;

class LoginUseCase
{
    public function login(LoginInputDto $inputDto): LoginOutputDto
    {
        $credentials = [
            'email' => $inputDto->email,
            'password' => $inputDto->password
        ];

        $token = Auth::attempt($credentials);

        if (!$token) {
            throw new UnauthorizedException();
        }

        return new LoginOutputDto((string)$token);
    }
}
