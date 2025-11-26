<?php

namespace App\Features\Line\SendLineMessage;

use Illuminate\Http\Resources\Json\JsonResource;

class SendLineMessageResultResource extends JsonResource
{
    public function __construct(
        private readonly string $message,
        private readonly int $customerId,
        private readonly string $type,
    ) {
        parent::__construct(null);
    }

    public function toArray($request): array
    {
        return [
            'message' => $this->message,
            'customer_id' => $this->customerId,
            'type' => $this->type,
        ];
    }
}
