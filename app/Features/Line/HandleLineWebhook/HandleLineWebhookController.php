<?php

namespace App\Features\Line\HandleLineWebhook;

use App\Http\Controllers\ApiResponseTrait;
use Illuminate\Http\JsonResponse;

class HandleLineWebhookController
{
    use ApiResponseTrait;

    public function __invoke(
        HandleLineWebhookRequest $request,
        HandleLineWebhookAction $action,
    ): JsonResponse {
        $result = $action->execute(
            $request->routeUserWebsiteId(),
            $request->routeConfigId(),
            $request,
        );

        return $this->responseSuccess(new LineWebhookResultResource($result['status']));
    }
}
