<?php

namespace App\Features\Widget\CreateWidgetChannel;

use App\Models\ChannelWebhookConfig;

class CreateWidgetChannelAction
{
    public function execute(array $data): ChannelWebhookConfig
    {
        return ChannelWebhookConfig::query()->updateOrCreate(
            [
                'user_website_id' => $data['user_website_id'],
                'channel' => 'widget',
            ],
            [
                'status' => 'active',
                'config' => [
                    'widget_id' => $data['widget_id'] ?? null,
                    'widget_name' => $data['widget_name'] ?? null,
                ],
            ],
        );
    }
}
