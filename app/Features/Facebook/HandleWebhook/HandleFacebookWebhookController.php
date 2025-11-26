<?php

namespace App\Features\Facebook\HandleWebhook;

use App\Exceptions\CustomException;
use App\Features\Facebook\HandleWebhook\Actions\CreateFacebookMessageAction;
use App\Features\Facebook\HandleWebhook\Actions\UpsertFacebookConversationAction;
use App\Features\Facebook\HandleWebhook\Actions\UpsertFacebookCustomerAction;
use App\Features\Facebook\HandleWebhook\Actions\VerifyFacebookSignatureAction;
use App\Http\Controllers\ApiResponseTrait;
use App\Models\ChannelWebhookConfig;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HandleFacebookWebhookController
{
    use ApiResponseTrait;

    public function __invoke(
        HandleFacebookWebhookRequest $request,
        Request $rawRequest,
        VerifyFacebookSignatureAction $verifyFacebookSignatureAction,
        UpsertFacebookCustomerAction $upsertFacebookCustomerAction,
        UpsertFacebookConversationAction $upsertFacebookConversationAction,
        CreateFacebookMessageAction $createFacebookMessageAction,
    ): JsonResponse {
        $userWebsiteId = $request->routeUserWebsiteId();
        $configId = $request->routeConfigId();

        $config = ChannelWebhookConfig::query()
            ->where('id', $configId)
            ->where('user_website_id', $userWebsiteId)
            ->where('channel', 'facebook')
            ->first();

        if (! $config) {
            throw new CustomException('Facebook config not found.', 404);
        }

        $appSecret = config('services.facebook.app_secret');
        $verifyFacebookSignatureAction->execute($rawRequest, $appSecret);

        $payload = $rawRequest->json()->all();
        $entries = $payload['entry'] ?? [];
        $events = collect($entries)
            ->map(fn (array $entry) => FacebookWebhookEvent::fromArray($entry))
            ->all();

        $pageToken = $config->config['access_token'] ?? null;
        if (! $pageToken) {
            throw new CustomException('Facebook page access token missing.');
        }

        foreach ($events as $event) {
            Log::info('facebook.webhook.event', $event->toArray());
            $customer = $upsertFacebookCustomerAction->execute($event, $userWebsiteId);
            $conversation = $upsertFacebookConversationAction->execute($customer);
            $createFacebookMessageAction->execute($conversation, $customer, $event);
        }

        return $this->responseSuccess(new FacebookWebhookResultResource(count($events)));
    }
}
