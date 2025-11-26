<?php

namespace App\Features\Widget\CreateAppAdapterChannel;

use App\Models\ChannelWebhookConfig;

class CreateAppAdapterChannelAction
{
    public function execute(array $data): ChannelWebhookConfig
    {
        return ChannelWebhookConfig::query()->updateOrCreate(
            [
                'user_website_id' => $data['user_website_id'],
                'channel' => 'app',
            ],
            [
                'status' => 'active',
                'config' => [
                    'domain_name' => $data['domain_name'],
                ],
            ],
        );
    }
}
