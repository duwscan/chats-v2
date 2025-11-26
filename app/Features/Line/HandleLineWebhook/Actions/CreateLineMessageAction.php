<?php

namespace App\Features\Line\HandleLineWebhook\Actions;

use App\Exceptions\CustomException;
use App\Models\ConversationModel;
use App\Models\CustomerModel;
use App\Models\MessageModel;
use LINE\Webhook\Model\ImageMessageContent;
use LINE\Webhook\Model\MessageEvent;
use LINE\Webhook\Model\TextMessageContent;

class CreateLineMessageAction
{
    public function execute(
        ConversationModel $conversation,
        CustomerModel $customer,
        MessageEvent $event,
        int $configId,
    ): MessageModel {
        $message = $event->getMessage();
        $messageType = $message->getType();
        $source = $event->getSource();

        if ($message instanceof TextMessageContent) {
            $messageText = $message->getText();
            $dbMessageType = 'text';
        } elseif ($message instanceof ImageMessageContent) {
            $messageText = null;
            $dbMessageType = 'attachment';
        } else {
            throw new CustomException('Unsupported LINE message type: '.$messageType);
        }

        $metadata = array_filter([
            'reply_token' => $event->getReplyToken(),
            'line_message_type' => $messageType,
            'config_id' => $configId,
            'raw_message' => method_exists($message, 'jsonSerialize') ? (array) $message->jsonSerialize() : [],
        ], fn ($value) => $value !== null);

        return MessageModel::query()->create([
            'conversation_id' => $conversation->id,
            'customer_id' => $customer->id,
            'message_text' => $messageText,
            'message_type' => $dbMessageType,
            'channel' => 'line',
            'channel_conversation_id' => $source->getUserId(),
            'metadata' => $metadata,
            'thread_id' => $message->getId(),
        ]);
    }
}
