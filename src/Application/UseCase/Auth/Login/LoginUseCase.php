<?php

namespace Application\UseCase\Auth\Login;

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

        if (!$token = Auth::attempt($credentials)) {
            throw new UnauthorizedException();
        }

        return new LoginOutputDto($token);
    }
}
