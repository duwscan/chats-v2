<?php

namespace App\Features\Facebook\CreateFacebookChannelOnCallback;

use App\Http\Controllers\ApiResponseTrait;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

#[Group('Facebook Channels')]
class FacebookCallbackController
{
    use ApiResponseTrait;

    /**
     * Handle Facebook OAuth callback and create channel configuration.
     *
     * @response FacebookCallbackResource
     *
     * @status 200
     */
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
