<?php

namespace App\Features\Line\HandleLineWebhook\Actions;

use App\Exceptions\CustomException;
use App\Features\Chat\Events\MessageReceived;
use App\Models\ConversationModel;
use App\Models\CustomerModel;
use App\Models\MessageModel;
use Illuminate\Support\Facades\Event;
use LINE\Webhook\Model\ImageMessageContent;
use LINE\Webhook\Model\MessageEvent;
use LINE\Webhook\Model\TextMessageContent;
use LINE\Webhook\Model\UserSource;

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

        $userId = $source instanceof UserSource ? $source->getUserId() : null;
        if ($userId === null) {
            throw new CustomException('LINE message source must be a user source');
        }

        $metadata = array_filter([
            'reply_token' => $event->getReplyToken(),
            'line_message_type' => $messageType,
            'config_id' => $configId,
            'raw_message' => (array) $message->jsonSerialize(),
        ], fn ($value) => $value !== null);

        $savedMessage = MessageModel::query()->create([
            'conversation_id' => $conversation->id,
            'customer_id' => $customer->id,
            'message_text' => $messageText,
            'message_type' => $dbMessageType,
            'channel' => 'line',
            'channel_conversation_id' => $userId,
            'metadata' => $metadata,
            'thread_id' => $message->getId(),
        ]);

        Event::dispatch(new MessageReceived(
            message: $savedMessage,
            conversation: $conversation,
            customer: $customer,
            channel: 'line',
        ));

        return $savedMessage;
    }
}
