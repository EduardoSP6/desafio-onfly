<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Infrastructure\Persistence\Models\User;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="UserResource",
 *     type="object",
 *
 *     @OA\Property(
 *          property="uuid",
 *          type="string",
 *          description="Uuid do usuário.",
 *     ),
 *
 *     @OA\Property(
 *          property="name",
 *          type="string",
 *          description="Nome do usuário."
 *     ),
 *
 *     @OA\Property(
 *          property="email",
 *          type="string",
 *          format="email",
 *          description="E-mail do usuário."
 *     ),
 * )
 */
class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var User $this */
        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'email' => $this->email,
        ];
    }
}
