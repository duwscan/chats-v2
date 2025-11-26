<?php

namespace App\Features\Facebook\HandleWebhook;

use App\Http\Controllers\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HandleFacebookWebhookController
{
    use ApiResponseTrait;

    public function __invoke(
        HandleFacebookWebhookRequest $request,
        HandleFacebookWebhookAction $action,
        Request $rawRequest,
    ): JsonResponse {
        $result = $action(
            $request->routeUserWebsiteId(),
            $request->routeConfigId(),
            $rawRequest,
        );

        return $this->responseSuccess(new FacebookWebhookResultResource($result['processed']));
    }
}
