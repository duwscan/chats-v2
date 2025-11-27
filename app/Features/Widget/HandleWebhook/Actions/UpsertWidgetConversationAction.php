<?php

namespace App\Features\Widget\HandleWebhook\Actions;

use App\Models\ConversationModel;
use App\Models\CustomerModel;

class UpsertWidgetConversationAction
{
    public function execute(CustomerModel $customer): ConversationModel
    {
        $conversation = ConversationModel::query()->firstOrCreate(
            [
                'customer_id' => $customer->id,
                'channel' => 'widget',
            ],
            [
                'status' => 'open',
                'last_message_at' => now(),
                'message_count' => 0,
            ],
        );

        $conversation->update([
            'last_message_at' => now(),
            'channel' => 'widget',
        ]);

        return $conversation;
    }
}
