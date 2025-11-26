<?php

namespace App\Features\Facebook\GenerateFacebookConnectUrl;

use Illuminate\Http\Resources\Json\JsonResource;

class FacebookAuthUrlResource extends JsonResource
{
    public function __construct(private readonly string $authorizationUrl)
    {
        parent::__construct(null);
    }

    public function toArray($request): array
    {
        return [
            'authorization_url' => $this->authorizationUrl,
        ];
    }
}
