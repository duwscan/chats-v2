<?php

namespace App\Features\Facebook\HandleWebhook\Actions;

use App\Models\CustomerModel;

class UpsertFacebookCustomerAction
{
    public function execute(array $message, string $userWebsiteId, string $pageId): CustomerModel
    {
        $sender = $message['sender']['id'] ?? null;
        $recipient = $message['recipient']['id'] ?? null;
        $isPageEcho = $sender === $pageId || ($message['message']['is_echo'] ?? false) === true;
        $customerExternalId = $isPageEcho ? $recipient : $sender;
        return CustomerModel::query()->firstOrCreate(
            [
                'channel' => 'facebook',
                'channel_user_id' => $customerExternalId,
                'user_website_id' => $userWebsiteId,
            ],
            [
                'display_name' => "Facebook User",
                'last_activity_at' => now(),
            ],
        );
    }
}
