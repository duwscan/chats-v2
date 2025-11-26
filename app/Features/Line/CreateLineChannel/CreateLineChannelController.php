<?php

namespace App\Features\Line\CreateLineChannel;

use App\Features\Line\LineChannelConfigResource;
use App\Http\Controllers\ApiResponseTrait;
use Illuminate\Http\JsonResponse;

class CreateLineChannelController
{
    use ApiResponseTrait;

    /**
     * Create a new LINE channel configuration.
     *
     * @response LineChannelConfigResource
     *
     * @status 200
     */
    public function __invoke(
        CreateLineChannelRequest $request,
        CreateLineChannelAction $action,
    ): JsonResponse {
        $config = $action->execute($request->validated());

        return $this->responseSuccess(new LineChannelConfigResource($config));
    }
}
