<?php

namespace App\Features\Widget\HandleWebhook\Actions;

use App\Models\ChannelWebhookConfig;
use App\Models\CustomerModel;

class UpsertWidgetCustomerAction
{
    public function execute(string $visitorId, int $userWebsiteId, ?ChannelWebhookConfig $config = null): CustomerModel
    {
        $customer = CustomerModel::query()->firstOrCreate(
            [
                'channel' => 'widget',
                'channel_user_id' => $visitorId,
                'user_website_id' => $userWebsiteId,
            ],
            [
                'display_name' => 'Widget Visitor',
                'last_activity_at' => now(),
                'channel_webhook_config_id' => $config?->id,
            ],
        );

        if (! $customer->wasRecentlyCreated) {
            $customer->update([
                'last_activity_at' => now(),
            ]);
        }

        if ($config && $customer->channel_webhook_config_id !== $config->id) {
            $customer->update([
                'channel_webhook_config_id' => $config->id,
            ]);
        }

        return $customer;
    }
}
