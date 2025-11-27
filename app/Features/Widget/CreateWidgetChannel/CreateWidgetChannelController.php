<?php

namespace App\Features\Widget\CreateWidgetChannel;

use App\Http\Controllers\ApiResponseTrait;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\JsonResponse;

#[Group('Widget Channels')]
class CreateWidgetChannelController
{
    use ApiResponseTrait;

    /**
     * Create a new Widget channel configuration.
     *
     * @response WidgetChannelConfigResource
     *
     * @status 200
     */
    public function __invoke(
        CreateWidgetChannelRequest $request,
        CreateWidgetChannelAction $action,
    ): JsonResponse {
        $config = $action->execute($request->validated());

        return $this->responseSuccess(new WidgetChannelConfigResource($config));
    }
}
