<?php

namespace App\Features\Line;

use Illuminate\Http\Resources\Json\JsonResource;

class LineChannelConfigResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'channel' => $this->channel,
            'status' => $this->status,
            'config' => $this->config,
            'user_website_id' => $this->user_website_id,
        ];
    }
}
