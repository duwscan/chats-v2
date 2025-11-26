<?php

namespace App\Features\Line\HandleLineWebhook\Actions;

use App\Models\ConversationModel;
use App\Models\CustomerModel;

class UpsertLineConversationAction
{
    public function execute(CustomerModel $customer): ConversationModel
    {
        $conversation = ConversationModel::query()->firstOrCreate(
            [
                'customer_id' => $customer->id,
                'channel' => 'line',
            ],
            [
                'status' => 'open',
                'last_message_at' => now(),
                'message_count' => 0,
            ],
        );

        $conversation->update([
            'last_message_at' => now(),
            'channel' => 'line',
        ]);

        return $conversation;
    }
}
