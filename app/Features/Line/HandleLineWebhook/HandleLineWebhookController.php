<?php

namespace App\Features\Line\HandleLineWebhook;

use App\Http\Controllers\ApiResponseTrait;
use App\Features\Line\HandleLineWebhook\LineWebhookResultResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HandleLineWebhookController
{
    use ApiResponseTrait;

    public function __invoke(
        HandleLineWebhookRequest $request,
        HandleLineWebhookAction $action,
    ): JsonResponse {
        $result = $action(
            $request->routeUserWebsiteId(),
            $request->routeConfigId(),
            $request,
        );

        return $this->responseSuccess(new LineWebhookResultResource($result['status']));
    }
}
