<?php

namespace App\Features\Widget\HandleWebhook\Actions;

use App\Features\Chat\Events\MessageReceived;
use App\Models\ConversationModel;
use App\Models\CustomerModel;
use App\Models\MessageModel;
use Illuminate\Support\Facades\Event;

class CreateWidgetMessageAction
{
    public function execute(
        ConversationModel $conversation,
        CustomerModel $customer,
        string $messageText,
        ?string $messageId = null,
        array $metadata = [],
    ): MessageModel {
        $savedMessage = MessageModel::query()->create([
            'conversation_id' => $conversation->id,
            'customer_id' => $customer->id,
            'message_text' => $messageText,
            'message_type' => 'text',
            'channel' => 'widget',
            'channel_conversation_id' => $customer->channel_user_id,
            'thread_id' => $messageId,
            'metadata' => $metadata,
        ]);

        Event::dispatch(new MessageReceived(
            message: $savedMessage,
            conversation: $conversation,
            customer: $customer,
            channel: 'widget',
        ));

        return $savedMessage;
    }
}
