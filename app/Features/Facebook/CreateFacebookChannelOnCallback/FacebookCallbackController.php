<?php

namespace App\Features\Facebook\CreateFacebookChannelOnCallback;

use App\Features\Facebook\CreateFacebookChannelOnCallback\FacebookCallbackResource;
use App\Http\Controllers\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class FacebookCallbackController
{
    use ApiResponseTrait;

    public function __invoke(
        HandleFacebookCallbackRequest $request,
        CreateFacebookChannelAction $action,
    ): JsonResponse|RedirectResponse {
        $payload = $action($request->validated());

        $redirectUrl = config('services.facebook.success_callback_redirect', '/');
        $redirectUrl = strtr($redirectUrl, [
            ':userWebsiteId' => $request->validated()['state'] ?? '',
        ]);

        if (! $request->expectsJson()) {
            return redirect()->away($redirectUrl);
        }

        return $this->responseSuccess(new FacebookCallbackResource($payload));
    }
}
