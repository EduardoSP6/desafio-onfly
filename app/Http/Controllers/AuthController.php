<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterUserRequest;
use App\Http\Resources\LoginResource;
use App\Http\Resources\UserResource;
use Application\Exception\UnauthorizedException;
use Application\UseCase\Auth\Login\LoginInputDto;
use Application\UseCase\Auth\Login\LoginUseCase;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    private LoginUseCase $loginUseCase;

    public function __construct(LoginUseCase $loginUseCase)
    {
        $this->loginUseCase = $loginUseCase;
    }

    public function login(LoginRequest $request): LoginResource
    {
        try {
            $inputDto = new LoginInputDto(
                $request->validated('email'),
                $request->validated('password'),
            );

            $outputDto = $this->loginUseCase->login($inputDto);

            return new LoginResource($outputDto->accessToken);
        } catch (UnauthorizedException $e) {
            abort($e->getCode(), $e->getMessage());
        } catch (Exception) {
            abort(500, "Falha ao realizar login");
        }
    }

    public function logout(): JsonResponse
    {
        Auth::logout();

        return response()->json([], 200);
    }

    public function me(): UserResource
    {
        return UserResource::make(Auth::user());
    }
}
