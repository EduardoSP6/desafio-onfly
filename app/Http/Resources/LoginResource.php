<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

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
