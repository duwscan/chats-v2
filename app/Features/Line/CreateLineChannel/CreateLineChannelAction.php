<?php

namespace App\Features\Line\CreateLineChannel;

use App\Models\ChannelWebhookConfigModel;

class CreateLineChannelAction
{
    public function execute(array $data): ChannelWebhookConfigModel
    {
        return ChannelWebhookConfigModel::query()->updateOrCreate(
            [
                'user_website_id' => $data['user_website_id'],
                'channel' => 'line',
            ],
            [
                'status' => 'active',
                'config' => [
                    'client_id' => $data['client_id'],
                    'client_secret' => $data['client_secret'],
                    'access_token' => $data['access_token'],
                    'channel_name' => $data['channel_name'] ?? null,
                ],
            ],
        );
    }
}
