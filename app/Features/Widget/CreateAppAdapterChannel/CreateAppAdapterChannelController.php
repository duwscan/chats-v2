<?php

namespace App\Features\Widget\CreateAppAdapterChannel;

use App\Features\Widget\WidgetChannelConfigResource;
use App\Http\Controllers\ApiResponseTrait;
use Illuminate\Http\JsonResponse;

class CreateAppAdapterChannelController
{
    use ApiResponseTrait;

    public function __invoke(
        CreateAppAdapterChannelRequest $request,
        CreateAppAdapterChannelAction $action,
    ): JsonResponse {
        $config = $action->execute($request->validated());

        return $this->responseSuccess(new WidgetChannelConfigResource($config));
    }
}
