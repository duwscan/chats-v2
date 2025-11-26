<?php

namespace App\Features\Line\HandleLineWebhook;

use Illuminate\Http\Resources\Json\JsonResource;

class LineWebhookResultResource extends JsonResource
{
    public function __construct(private readonly int $processed)
    {
        parent::__construct(null);
    }

    public function toArray($request): array
    {
        return [
            'status' => 'ok',
            'processed' => $this->processed,
        ];
    }
}
