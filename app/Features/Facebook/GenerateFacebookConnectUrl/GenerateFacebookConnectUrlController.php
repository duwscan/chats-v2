<?php

namespace App\Features\Facebook\GenerateFacebookConnectUrl;

use App\Http\Controllers\ApiResponseTrait;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\JsonResponse;

#[Group('Facebook Channels')]
class GenerateFacebookConnectUrlController
{
    use ApiResponseTrait;

    /**
     * Generate Facebook OAuth connect URL for a user website.
     *
     * @response FacebookAuthUrlResource
     *
     * @status 200
     */
    public function __invoke(
        GenerateFacebookConnectUrlRequest $request,
        GenerateFacebookConnectUrlAction $action,
    ): JsonResponse {
        $link = $action($request->routeParamUserWebsiteId());

        return $this->responseSuccess(new FacebookAuthUrlResource($link));
    }
}
