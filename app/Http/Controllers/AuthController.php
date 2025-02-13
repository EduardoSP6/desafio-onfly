<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\LoginResource;
use App\Http\Resources\UserResource;
use Application\Exception\UnauthorizedException;
use Application\UseCases\Auth\Login\LoginInputDto;
use Application\UseCases\Auth\Login\LoginUseCase;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use OpenApi\Annotations as OA;

class AuthController extends Controller
{
    private LoginUseCase $loginUseCase;

    public function __construct(LoginUseCase $loginUseCase)
    {
        $this->loginUseCase = $loginUseCase;
    }

    /**
     * @OA\Post(
     *     path="/api/v1/login",
     *     operationId="Login",
     *     tags={"Auth"},
     *     summary="Login de usuários",
     *     description="Efetua login de usuários por e-mail e senha",
     *
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  type="object",
     *                  required={"email", "password"},
     *                  @OA\Property(
     *                      property="email",
     *                      type="string",
     *                      format="email",
     *                      description="E-mail do usuário",
     *                  ),
     *                  @OA\Property(
     *                      property="password",
     *                      type="string",
     *                      format="password",
     *                      description="Senha do usuário"
     *                  ),
     *              ),
     *          ),
     *     ),
     *
     *     @OA\Response(
     *          response="200",
     *          description="OK",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(ref="#/components/schemas/LoginResource")
     *          ),
     *    ),
     *
     *     @OA\Response(
     *          response="422",
     *          description="Unprocessable entity"
     *     ),
     *
     *     @OA\Response(
     *          response="401",
     *          description="Unauthorized"
     *     ),
     *
     *     @OA\Response(
     *          response="500",
     *          description="Server error"
     *     ),
     * )
     */
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

    /**
     * @OA\Post(
     *     path="/api/v1/logout",
     *     operationId="Logout",
     *     tags={"Auth"},
     *     summary="Logout do sistema",
     *     description="Remove autenticação do usuário logado.",
     *     security={{"BearerAuth":{}}},
     *
     *     @OA\Response(
     *          response="200",
     *          description="OK",
     *     ),
     *
     *     @OA\Response(
     *          response="401",
     *          description="Unauthorized"
     *     ),
     * )
     */
    public function logout(): JsonResponse
    {
        Auth::logout();

        return response()->json([], 200);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/me",
     *     operationId="Me",
     *     tags={"Auth"},
     *     summary="Dados do usuário logado.",
     *     description="Retorna dados do usuário logado.",
     *     security={{"BearerAuth":{}}},
     *
     *     @OA\Response(
     *          response="200",
     *          description="OK",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(ref="#/components/schemas/UserResource")
     *          ),
     *    ),
     *
     *     @OA\Response(
     *          response="401",
     *          description="Unauthorized"
     *     ),
     * )
     */
    public function me(): UserResource
    {
        return UserResource::make(Auth::user());
    }
}
