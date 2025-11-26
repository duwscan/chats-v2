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
        Request $rawRequest,
        VerifyFacebookSignatureAction $verifyFacebookSignatureAction,
        UpsertFacebookCustomerAction $upsertFacebookCustomerAction,
        UpsertFacebookConversationAction $upsertFacebookConversationAction,
        CreateFacebookMessageAction $createFacebookMessageAction,
    ): JsonResponse {
        $appSecret = config('services.facebook.app_secret');
        $verifyFacebookSignatureAction->execute($rawRequest, $appSecret);

        $payload = $rawRequest->json()->all();
        $entries = $payload['entry'] ?? [];
        $events = collect($entries)
            ->map(fn (array $entry) => FacebookWebhookEvent::fromArray($entry))
            ->all();

        foreach ($events as $event) {
            Log::info('facebook.webhook.event', $event->toArray());
            $config = ChannelWebhookConfig::query()
                ->where('channel', 'facebook')
                ->where('config->page_id', $event->pageId)
                ->first();

            if (! $config) {
                throw new CustomException('Facebook config not found.', 404);
            }

            $pageToken = $config->config['access_token'] ?? null;

            if (! $pageToken) {
                throw new CustomException('Facebook page access token missing.');
            }

            $userWebsiteId = $config->user_website_id;
            $customer = $upsertFacebookCustomerAction->execute($event, $userWebsiteId);
            $conversation = $upsertFacebookConversationAction->execute($customer);
            $createFacebookMessageAction->execute($conversation, $customer, $event);
        }

        return $this->responseSuccess(new FacebookWebhookResultResource(count($events)));
    }
}
