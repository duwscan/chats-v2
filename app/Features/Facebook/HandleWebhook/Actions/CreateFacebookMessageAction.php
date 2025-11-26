<?php

namespace App\Features\Facebook\HandleWebhook\Actions;

use App\Models\ConversationModel;
use App\Models\CustomerModel;
use App\Models\MessageModel;
use RuntimeException;

class CreateFacebookMessageAction
{
    public function execute(ConversationModel $conversation, CustomerModel $customer, array $message, bool $isPageEcho): void
    {
        $type = 'unknown';

        if (! empty($message['message']['text'])) {
            $type = 'text';
        }

        if (! empty($message['message']['attachments'])) {
            $type = 'attachment';
        }

        $mid = $message['message']['mid'] ?? null;
        if($type == 'text') {
            $content = $message['message']['text'];
            $messageModel = MessageModel::query()
                ->where('channel', 'facebook')
                ->where('channel_conversation_id', $mid)
                ->first();
            throw_if($messageModel, new RuntimeException('Already handled message with mid: '.$message['message']['mid']));
            if(!$isPageEcho) {
                MessageModel::create([
                    "conversation_id" => $conversation->id,
                    "customer_id" => $customer->id,
                    "message_type" => $type,
                    "message_text" => $content,
                    "channel" => 'facebook',
                    "channel_conversation_id" => $mid,
                ]);
            } else {
                MessageModel::create([
                    "conversation_id" => $conversation->id,
                    "message_type" => $type,
                    "message_text" => $content,
                    "channel" => 'facebook',
                    "channel_conversation_id" => $mid,
                ]);
            }
        }
//        TODO: handle image
    }





}
