<?php

namespace App\Features\Facebook\HandleWebhook\Actions;

use App\Features\Facebook\HandleWebhook\FacebookWebhookEvent;
use App\Models\CustomerModel;

class UpsertFacebookCustomerAction
{
    public function execute(FacebookWebhookEvent $event, string $userWebsiteId): CustomerModel
    {
        return CustomerModel::query()->firstOrCreate(
            [
                'channel' => 'facebook',
                'channel_user_id' => $event->senderId,
                'user_website_id' => $userWebsiteId,
            ],
            [
                'display_name' => $event->senderId,
                'last_activity_at' => now(),
            ],
        );
    }
}
