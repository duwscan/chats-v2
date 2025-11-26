<?php

namespace App\Features\Facebook\HandleWebhook;

use App\Exceptions\CustomException;
use App\Models\ChannelWebhookConfig;
use App\Models\ConversationModel;
use App\Models\CustomerModel;
use App\Models\MessageModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class HandleFacebookWebhookAction
{
    public function __invoke(int $userWebsiteId, int $configId, Request $request): array
    {
        $config = ChannelWebhookConfig::query()
            ->where('id', $configId)
            ->where('user_website_id', $userWebsiteId)
            ->where('channel', 'facebook')
            ->first();

        if (! $config) {
            throw new CustomException('Facebook config not found.', 404);
        }

        $appSecret = config('services.facebook.app_secret');
        if (empty($appSecret)) {
            throw new CustomException('Facebook app secret not configured.');
        }

        $body = $request->getContent();
        $expectedSignature = 'sha256='.hash_hmac('sha256', $body, $appSecret);
        $signatureHeader = $request->header('X-Hub-Signature-256');

        if (! hash_equals($expectedSignature, (string) $signatureHeader)) {
            throw new CustomException('Invalid Facebook signature.', 403);
        }

        $payload = $request->json()->all();
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

            $customer = CustomerModel::query()->firstOrCreate(
                [
                    'channel' => 'facebook',
                    'channel_user_id' => $event->senderId,
                    'user_website_id' => $userWebsiteId,
                ],
                [
                    'display_name' => $event->senderId,
                    'last_activity_at' => now(),
                ],
            );

            $conversation = ConversationModel::query()->firstOrCreate(
                [
                    'customer_id' => $customer->id,
                    'channel' => 'facebook',
                ],
                [
                    'status' => 'open',
                    'last_message_at' => now(),
                    'message_count' => 0,
                ],
            );

            $messageText = $event->text ?? '';
            MessageModel::query()->create([
                'conversation_id' => $conversation->id,
                'customer_id' => $customer->id,
                'message_text' => $messageText,
                'message_type' => 'text',
                'channel' => 'facebook',
                'channel_conversation_id' => $event->recipientId,
                'thread_id' => $event->mid,
                'metadata' => [
                    'page_id' => $event->pageId,
                    'attachments' => $event->attachments,
                ],
            ]);

            $conversation->update([
                'last_message_at' => now(),
            ]);
        }

        return [
            'processed' => count($events),
        ];
    }
}
