<?php

namespace App\Features\Facebook\CreateFacebookChannelOnCallback;

use Illuminate\Http\Resources\Json\JsonResource;

class FacebookPageResource extends JsonResource
{
    public function __construct(private readonly FacebookPage $page)
    {
        parent::__construct(null);
    }

    public function toArray($request): array
    {
        return $this->page->toArray();
    }
}
