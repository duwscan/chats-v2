<?php

namespace App\Features\Facebook\CreateFacebookChannelOnCallback;

use Illuminate\Http\Resources\Json\JsonResource;

class FacebookCallbackResource extends JsonResource
{
    /**
     * @param array{access_token:string,pages:FacebookPage[]} $payload
     */
    public function __construct(private readonly array $payload)
    {
        parent::__construct(null);
    }

    public function toArray($request): array
    {
        return [
            'access_token' => $this->payload['access_token'],
            'pages' => array_map(
                fn (FacebookPage $page) => (new FacebookPageResource($page))->toArray($request),
                $this->payload['pages']
            ),
        ];
    }
}
