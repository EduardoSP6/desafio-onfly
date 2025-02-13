<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="LoginResource",
 *     type="object",
 *
 *     @OA\Property(
 *          property="access_token",
 *          type="string",
 *          description="Token de acesso JWT",
 *     ),
 *
 *     @OA\Property(
 *          property="token_type",
 *          type="string",
 *          description="Tipo do token"
 *     ),
 *
 *     @OA\Property(
 *          property="expires_in",
 *          type="number",
 *          format="integer",
 *          description="Tempo de expiração"
 *     ),
 * )
 */
class LoginResource extends JsonResource
{
    private string $accessToken;

    public function __construct(string $accessToken)
    {
        parent::__construct($this);
        $this->accessToken = $accessToken;
    }

    public function toArray($request): array
    {
        return [
            'access_token' => $this->accessToken,
            'token_type' => 'bearer',
            'expires_in' => config('jwt.ttl') * 60
        ];
    }
}
