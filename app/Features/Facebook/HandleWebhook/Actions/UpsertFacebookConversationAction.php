<?php

namespace App\Features\Facebook\HandleWebhook\Actions;

use App\Models\ConversationModel;
use App\Models\CustomerModel;

class UpsertFacebookConversationAction
{
    public function execute(CustomerModel $customer): ConversationModel
    {
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

        $conversation->update([
            'last_message_at' => now(),
        ]);

        return $conversation;
    }
}
