<?php

namespace App\Features\Line\UpdateLineChannel;

use App\Exceptions\CustomException;
use App\Models\ChannelWebhookConfig;

class UpdateLineChannelAction
{
    public function __invoke(int $configId, array $data): ChannelWebhookConfig
    {
        $config = ChannelWebhookConfig::query()
            ->where('id', $configId)
            ->where('channel', 'line')
            ->first();

        if (! $config) {
            throw new CustomException('LINE config not found.', 404);
        }

        $config->update([
            'user_website_id' => $data['user_website_id'],
            'status' => 'active',
            'config' => [
                'client_id' => $data['client_id'],
                'client_secret' => $data['client_secret'],
                'access_token' => $data['access_token'],
                'channel_name' => $data['channel_name'] ?? null,
            ],
        ]);

        return $config->refresh();
    }
}
