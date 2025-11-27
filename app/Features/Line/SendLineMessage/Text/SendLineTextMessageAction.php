<?php

namespace App\Features\Line\SendLineMessage\Text;

use App\Exceptions\CustomException;
use App\Features\Chat\Events\MessageSent;
use App\Features\Line\LineChannel;
use App\Models\ChannelWebhookConfig;
use App\Models\ConversationModel;
use App\Models\CustomerModel;
use App\Models\MessageModel;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use LINE\Clients\MessagingApi\Api\MessagingApiApi;
use LINE\Clients\MessagingApi\ApiException;
use LINE\Clients\MessagingApi\Configuration;
use LINE\Clients\MessagingApi\Model\PushMessageRequest;
use LINE\Clients\MessagingApi\Model\ReplyMessageRequest;
use LINE\Clients\MessagingApi\Model\TextMessage;

class SendLineTextMessageAction
{
    public function execute(CustomerModel $customer, SendLineTextMessageData $data): void
    {
        try {
            if (empty($data->text)) {
                Log::warning('line.send_message.empty_text', [
                    'customer_id' => $customer->id,
                    'channel_user_id' => $customer->channel_user_id,
                ]);

                return;
            }

            if ($customer->channel !== 'line') {
                throw new CustomException('Customer is not a LINE customer.', 400);
            }

            if (empty($customer->channel_user_id)) {
                throw new CustomException('Customer channel_user_id is missing.', 400);
            }

            // Get LINE channel configuration from customer
            $config = null;

            if ($customer->channel_webhook_config_id) {
                $config = ChannelWebhookConfig::query()
                    ->where('id', $customer->channel_webhook_config_id)
                    ->where('channel', 'line')
                    ->first();
            }

            if (! $config) {
                throw new CustomException('LINE config not found for customer. config_id: '.($customer->channel_webhook_config_id ?? 'none').' or user_website_id: '.($customer->user_website_id ?? 'none'), 404);
            }

            $channelConfig = LineChannel::fromArray($config->config ?? []);

            $sdkConfig = new Configuration;
            $sdkConfig->setAccessToken($channelConfig->getAccessToken());
            $messagingApi = new MessagingApiApi(config: $sdkConfig);

            $textMessage = new TextMessage([
                'type' => 'text',
                'text' => $data->text,
            ]);

            $recipientId = $customer->channel_user_id;
            $apiMethod = 'push';

            if (! empty($data->replyToken)) {
                $replyMessageRequest = new ReplyMessageRequest([
                    'replyToken' => $data->replyToken,
                    'messages' => [$textMessage],
                ]);

                $messagingApi->replyMessage($replyMessageRequest);
                $apiMethod = 'reply';

                Log::info('line.reply_message.sent', [
                    'customer_id' => $customer->id,
                    'recipient_id' => $recipientId,
                    'message_text' => $data->text,
                    'config_id' => $customer->channel_webhook_config_id,
                    'reply_token' => $data->replyToken,
                ]);
            } else {
                $pushMessageRequest = new PushMessageRequest([
                    'to' => $recipientId,
                    'messages' => [$textMessage],
                ]);

                $messagingApi->pushMessage($pushMessageRequest);

                Log::info('line.push_message.sent', [
                    'customer_id' => $customer->id,
                    'recipient_id' => $recipientId,
                    'message_text' => $data->text,
                    'config_id' => $customer->channel_webhook_config_id,
                ]);
            }

            if ($data->conversationId !== null) {
                $conversation = ConversationModel::query()->find($data->conversationId);

                $savedMessage = MessageModel::query()->create([
                    'conversation_id' => $data->conversationId,
                    'customer_id' => $customer->id,
                    'agent_id' => $data->agentId,
                    'message_text' => $data->text,
                    'message_type' => 'text',
                    'thread_id' => null,
                    'reply_to_id' => $data->replyToId,
                    'channel' => 'line',
                    'channel_conversation_id' => $recipientId,
                    'metadata' => [
                        'sent_via' => $apiMethod === 'reply' ? 'line_reply_api' : 'line_push_api',
                        'sent_at' => now()->toIso8601String(),
                        'reply_token_used' => ! empty($data->replyToken),
                    ],
                ]);

                if ($conversation) {
                    Event::dispatch(new MessageSent(
                        message: $savedMessage,
                        conversation: $conversation,
                        customer: $customer,
                        channel: 'line',
                    ));
                }
            }
        } catch (ApiException $e) {
            Log::error('line.api_error', [
                'error' => $e->getMessage(),
                'response_body' => $e->getResponseBody(),
                'customer_id' => $customer->id,
                'channel_user_id' => $customer->channel_user_id,
            ]);

            throw $e;
        } catch (\Throwable $e) {
            Log::error('line.send_message.error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'customer_id' => $customer->id,
                'channel_user_id' => $customer->channel_user_id,
            ]);

            throw $e;
        }
    }
}
