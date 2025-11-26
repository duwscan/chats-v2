<?php

namespace App\Features\Line\UpdateLineChannel;

use App\Features\Line\LineChannelConfigResource;
use App\Http\Controllers\ApiResponseTrait;
use Illuminate\Http\JsonResponse;

class UpdateLineChannelController
{
    use ApiResponseTrait;

    public function __invoke(
        UpdateLineChannelConfigRequest $request,
        UpdateLineChannelAction $action,
    ): JsonResponse {
        $config = $action->execute($request->routeConfigId(), $request->validated());

        return $this->responseSuccess(new LineChannelConfigResource($config));
    }
}
