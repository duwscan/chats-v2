<?php

namespace App\Features\Facebook\HandleWebhook\Actions;

use App\Features\Facebook\HandleWebhook\FacebookWebhookMessage;
use App\Models\ConversationModel;
use App\Models\CustomerModel;
use App\Models\MessageModel;
use RuntimeException;

class CreateFacebookMessageAction
{
    public function execute(ConversationModel $conversation, CustomerModel $customer, FacebookWebhookMessage $message): MessageModel
    {
        if ($message->messageType === 'unknown') {
            throw new RuntimeException('Unsupported Facebook message type: '.$message->messageType);
        }

        $existingMessage = MessageModel::query()
            ->where('channel', 'facebook')
            ->where('channel_conversation_id', $message->mid)
            ->first();

        throw_if($existingMessage, new RuntimeException('Already handled message with mid: '.$message->mid));

        $data = [
            'conversation_id' => $conversation->id,
            'message_type' => $message->messageType,
            'message_text' => $message->text,
            'channel' => 'facebook',
            'channel_conversation_id' => $message->mid,
        ];

        if (! $message->isPageEcho) {
            $data['customer_id'] = $customer->id;
        }

        return MessageModel::query()->create($data);
    }
}
