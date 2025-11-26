<?php

namespace App\Features\Facebook\VerifyFacebookWebhook;

use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

#[Group('Facebook Webhooks')]
class VerifyFacebookWebhookController
{
    /**
     * Verify Facebook webhook subscription.
     * This endpoint is used by Facebook to verify webhook subscription during setup.
     *
     * @response string
     *
     * @status 200
     */
    public function __invoke(Request $request)
    {
        if ($request->isMethod('GET')
            && $request->input('hub_mode') === 'subscribe'
            && $request->input('hub_verify_token') === config('services.facebook.webhook_verify_token')
            && $request->filled('hub_challenge')) {
            return response($request->input('hub_challenge'), Response::HTTP_OK, ['Content-Type' => 'text/plain']);
        }

        return response('Forbidden', Response::HTTP_FORBIDDEN, ['Content-Type' => 'text/plain']);
    }
}
