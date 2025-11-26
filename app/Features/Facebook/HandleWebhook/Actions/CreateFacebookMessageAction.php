<?php

namespace App\Features\Facebook\HandleWebhook\Actions;

use App\Features\Facebook\HandleWebhook\FacebookWebhookEvent;
use App\Models\ConversationModel;
use App\Models\CustomerModel;
use App\Models\MessageModel;

class CreateFacebookMessageAction
{
    public function execute(ConversationModel $conversation, CustomerModel $customer, FacebookWebhookEvent $event): void
    {
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
    }
}
