<?php

namespace App\Features\Widget\UpdateAppAdapterChannel;

use App\Features\Widget\WidgetChannelConfigResource;
use App\Http\Controllers\ApiResponseTrait;
use Illuminate\Http\JsonResponse;

class UpdateAppAdapterChannelController
{
    use ApiResponseTrait;

    public function __invoke(
        UpdateAppAdapterChannelRequest $request,
        UpdateAppAdapterChannelAction $action,
    ): JsonResponse {
        $config = $action->execute($request->routeConfigId(), $request->validated());

        return $this->responseSuccess(new WidgetChannelConfigResource($config));
    }
}
