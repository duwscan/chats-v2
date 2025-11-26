<?php

namespace App\Features\Facebook\HandleWebhook;

use Illuminate\Http\Resources\Json\JsonResource;

class FacebookWebhookResultResource extends JsonResource
{
    public function __construct(private readonly int $processed)
    {
        parent::__construct(null);
    }

    public function toArray($request): array
    {
        return [
            'processed' => $this->processed,
        ];
    }
}
