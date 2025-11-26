<?php

namespace App\Features\Facebook\HandleWebhook;

use App\Exceptions\CustomException;
use App\Features\Facebook\HandleWebhook\Actions\CreateFacebookAttachmentMessageAction;
use App\Features\Facebook\HandleWebhook\Actions\CreateFacebookMessageAction;
use App\Features\Facebook\HandleWebhook\Actions\ExtractFacebookMessageAction;
use App\Features\Facebook\HandleWebhook\Actions\UpsertFacebookConversationAction;
use App\Features\Facebook\HandleWebhook\Actions\UpsertFacebookCustomerAction;
use App\Features\Facebook\HandleWebhook\Actions\VerifyFacebookSignatureAction;
use App\Http\Controllers\ApiResponseTrait;
use App\Models\ChannelWebhookConfig;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class HandleFacebookWebhookController
{
    use ApiResponseTrait;

    /**
     * Handle incoming Facebook webhook events.
     *
     * @response FacebookWebhookResultResource
     *
     * @status 200
     */
    public function __invoke(
        Request $rawRequest,
        VerifyFacebookSignatureAction $verifyFacebookSignatureAction,
        ExtractFacebookMessageAction $extractFacebookMessageAction,
        UpsertFacebookCustomerAction $upsertFacebookCustomerAction,
        UpsertFacebookConversationAction $upsertFacebookConversationAction,
        CreateFacebookMessageAction $createFacebookMessageAction,
        CreateFacebookAttachmentMessageAction $createFacebookAttachmentMessageAction,
    ): JsonResponse {
        $appSecret = config('services.facebook.app_secret');
        $verifyFacebookSignatureAction->execute($rawRequest, $appSecret);

        $payload = $rawRequest->json()->all();
        $entries = $payload['entry'] ?? [];
        foreach ($entries as $event) {
            if (! is_array($event)) {
                continue;
            }

            Log::info('facebook.webhook.event', $event);

            $pageId = $event['id'] ?? null;
            throw_if(! $pageId, new CustomException('Facebook page ID missing in webhook event.'));
            $config = ChannelWebhookConfig::query()
                ->where('channel', 'facebook')
                ->where('config->page_id', $pageId)
                ->first();

            if (! $config) {
                throw new CustomException('Facebook config not found.', 404);
            }

            $pageToken = $config->config['access_token'] ?? null;

            if (! $pageToken) {
                throw new CustomException('Facebook page access token missing.');
            }

            $messages = $event['messaging'] ?? null;

            if (! is_array($messages) || $messages === []) {
                throw new RuntimeException('Invalid Facebook webhook payload: missing messaging array');
            }
            $userWebsiteId = $config->user_website_id;
            foreach ($messages as $message) {
                if (! is_array($message)) {
                    continue;
                }

                $messageDto = $extractFacebookMessageAction->execute($message, (string) $pageId);
                $customer = $upsertFacebookCustomerAction->execute($messageDto, $userWebsiteId, $config);
                $conversation = $upsertFacebookConversationAction->execute($customer);
                if ($messageDto->isAttachment()) {
                    $createFacebookAttachmentMessageAction->execute($conversation, $customer, $messageDto);
                } else {
                    $createFacebookMessageAction->execute($conversation, $customer, $messageDto);
                }
            }
        }

        return $this->responseSuccess(new FacebookWebhookResultResource(count($entries)));
    }
}
