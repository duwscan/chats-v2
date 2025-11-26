<?php

namespace App\Features\Facebook\GenerateFacebookConnectUrl;

use App\Http\Controllers\ApiResponseTrait;
use Illuminate\Http\JsonResponse;

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
