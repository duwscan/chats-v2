<?php

namespace App\Features\Facebook\HandleWebhook\Actions;

use App\Features\Facebook\HandleWebhook\FacebookWebhookMessage;
use App\Models\CustomerModel;

class UpsertFacebookCustomerAction
{
    public function execute(FacebookWebhookMessage $message, string $userWebsiteId): CustomerModel
    {
        return CustomerModel::query()->firstOrCreate(
            [
                'channel' => 'facebook',
                'channel_user_id' => $message->customerExternalId,
                'user_website_id' => $userWebsiteId,
            ],
            [
                'display_name' => 'Facebook User',
                'last_activity_at' => now(),
            ],
        );
    }
}
