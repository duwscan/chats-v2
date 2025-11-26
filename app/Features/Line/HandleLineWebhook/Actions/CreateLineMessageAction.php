<?php

namespace App\Features\Line\HandleLineWebhook\Actions;

use App\Exceptions\CustomException;
use App\Features\Line\HandleLineWebhook\LineWebhookEvent;
use App\Models\ConversationModel;
use App\Models\CustomerModel;
use App\Models\MessageModel;

class CreateLineMessageAction
{
    public function execute(
        ConversationModel $conversation,
        CustomerModel $customer,
        LineWebhookEvent $event,
        int $configId,
    ): MessageModel {
        if ($event->isText()) {
            $messageText = $event->text ?? '';
            $messageType = 'text';
        } elseif ($event->isImage()) {
            $messageText = null;
            $messageType = 'attachment';
        } else {
            throw new CustomException('Unsupported LINE message type: '.$event->messageType);
        }

        $metadata = array_filter([
            'reply_token' => $event->replyToken,
            'line_message_type' => $event->messageType,
            'config_id' => $configId,
            'raw_message' => $event->rawMessage,
        ], fn ($value) => $value !== null);

        return MessageModel::query()->create([
            'conversation_id' => $conversation->id,
            'customer_id' => $customer->id,
            'message_text' => $messageText,
            'message_type' => $messageType,
            'channel' => 'line',
            'channel_conversation_id' => $event->userId,
            'metadata' => $metadata,
            'thread_id' => $event->messageId,
        ]);
    }
}
