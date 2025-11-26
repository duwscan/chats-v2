<?php

namespace App\Features\Line\HandleLineWebhook;

use Illuminate\Http\Resources\Json\JsonResource;

class LineWebhookResultResource extends JsonResource
{
    public function __construct(private readonly string $status)
    {
        parent::__construct(null);
    }

    public function toArray($request): array
    {
        return [
            'status' => $this->status,
        ];
    }
}
