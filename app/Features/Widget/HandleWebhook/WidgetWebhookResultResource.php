<?php

namespace App\Features\Widget\HandleWebhook;

use Illuminate\Http\Resources\Json\JsonResource;

class WidgetWebhookResultResource extends JsonResource
{
    public function __construct(public readonly int $processedCount)
    {
        parent::__construct(null);
    }

    public function toArray($request): array
    {
        return [
            'processed' => $this->processedCount,
            'message' => 'Widget webhook processed successfully',
        ];
    }
}
