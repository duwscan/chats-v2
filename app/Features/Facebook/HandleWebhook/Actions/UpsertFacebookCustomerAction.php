<?php

namespace App\Features\Facebook\HandleWebhook\Actions;

use App\Features\Facebook\HandleWebhook\FacebookWebhookMessage;
use App\Models\ChannelWebhookConfig;
use App\Models\CustomerModel;

class UpsertFacebookCustomerAction
{
    public function execute(FacebookWebhookMessage $message, string $userWebsiteId, ?ChannelWebhookConfig $config = null): CustomerModel
    {
        $customer = CustomerModel::query()->firstOrCreate(
            [
                'channel' => 'facebook',
                'channel_user_id' => $message->customerExternalId,
                'user_website_id' => $userWebsiteId,
            ],
            [
                'display_name' => 'Facebook User',
                'last_activity_at' => now(),
                'channel_webhook_config_id' => $config?->id,
            ],
        );

        if ($config && $customer->channel_webhook_config_id !== $config->id) {
            $customer->update([
                'channel_webhook_config_id' => $config->id,
            ]);
        }

        return $customer;
    }
}
