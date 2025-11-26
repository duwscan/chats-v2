<?php

namespace App\Features\Line\UpdateLineChannel;

use App\Features\Line\LineChannelConfigResource;
use App\Http\Controllers\ApiResponseTrait;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\JsonResponse;

#[Group('LINE Channels')]
class UpdateLineChannelController
{
    use ApiResponseTrait;

    /**
     * Update an existing LINE channel configuration.
     *
     * @response LineChannelConfigResource
     *
     * @status 200
     */
    public function __invoke(
        UpdateLineChannelConfigRequest $request,
        UpdateLineChannelAction $action,
    ): JsonResponse {
        $config = $action->execute($request->routeConfigId(), $request->validated());

        return $this->responseSuccess(new LineChannelConfigResource($config));
    }
}
