<?php

namespace App\Features\Facebook\SendFacebookMessage\Text;

use App\Features\Chat\Events\MessageSent;
use App\Models\ChannelWebhookConfig;
use App\Models\ConversationModel;
use App\Models\CustomerModel;
use App\Models\MessageModel;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class SendFacebookTextMessageAction
{
    public function execute(CustomerModel $customer, FacebookTextMessage $message): void
    {
        try {
            if (empty($message->text)) {
                Log::warning('facebook.send_message.empty_text', [
                    'customer_id' => $customer->id,
                    'channel_user_id' => $customer->channel_user_id,
                ]);

                return;
            }

            $conversationId = $customer->channel_user_id;

            if (empty($conversationId)) {
                throw new RuntimeException('Customer channel_user_id is required to send Facebook message');
            }

            // Get Facebook channel configuration from customer
            if (! $customer->channel_webhook_config_id) {
                throw new RuntimeException('Customer channel_webhook_config_id is required to send Facebook message');
            }

            $channelWebhookConfig = ChannelWebhookConfig::query()
                ->where('id', $customer->channel_webhook_config_id)
                ->where('channel', 'facebook')
                ->first();

            if (! $channelWebhookConfig) {
                throw new RuntimeException('Facebook channel configuration not found for customer. config_id: '.$customer->channel_webhook_config_id);
            }

            $facebookConfig = $channelWebhookConfig->config ?? [];

            if (! is_array($facebookConfig)) {
                throw new RuntimeException('Invalid Facebook channel configuration type');
            }

            $accessToken = $facebookConfig['access_token'] ?? null;

            if (! $accessToken) {
                throw new RuntimeException('No access token found in Facebook configuration');
            }

            // Send message via Facebook Graph API
            $response = Http::post('https://graph.facebook.com/v18.0/me/messages', [
                'recipient' => ['id' => $conversationId],
                'message' => ['text' => $message->text],
                'access_token' => $accessToken,
            ]);

            if ($response->successful()) {
                Log::info('facebook.message.sent', [
                    'recipient_id' => $conversationId,
                    'message_length' => strlen($message->text),
                    'config_id' => $customer->channel_webhook_config_id,
                    'customer_id' => $customer->id,
                ]);

                // Save the sent message to database if conversation_id provided
                if ($message->conversationId) {
                    $conversation = ConversationModel::query()->find($message->conversationId);

                    $savedMessage = MessageModel::query()->create([
                        'conversation_id' => $message->conversationId,
                        'customer_id' => null, // Message is from agent/AI, not customer
                        'agent_id' => $message->agentId,
                        'message_text' => $message->text,
                        'message_type' => 'text',
                        'thread_id' => null,
                        'reply_to_id' => $message->replyToId,
                        'channel' => 'facebook',
                        'channel_conversation_id' => $conversationId,
                        'metadata' => [
                            'sent_via' => 'facebook_send_api',
                            'sent_at' => now()->toIso8601String(),
                        ],
                    ]);

                    if ($conversation) {
                        Event::dispatch(new MessageSent(
                            message: $savedMessage,
                            conversation: $conversation,
                            customer: $customer,
                            channel: 'facebook',
                        ));
                    }
                }
            } else {
                Log::error('facebook.message.send_failed', [
                    'recipient_id' => $conversationId,
                    'status' => $response->status(),
                    'response' => $response->body(),
                ]);

                throw new RuntimeException('Failed to send Facebook message: '.$response->body());
            }
        } catch (\Exception $e) {
            Log::error('facebook.send_message.error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'customer_id' => $customer->id,
                'channel_user_id' => $customer->channel_user_id,
                'message_text' => $message->text,
            ]);

            throw $e;
        }
    }
}
