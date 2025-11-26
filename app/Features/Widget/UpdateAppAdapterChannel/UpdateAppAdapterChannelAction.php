<?php

namespace App\Features\Widget\UpdateAppAdapterChannel;

use App\Exceptions\CustomException;
use App\Models\ChannelWebhookConfig;

class UpdateAppAdapterChannelAction
{
    public function execute(int $configId, array $data): ChannelWebhookConfig
    {
        $config = ChannelWebhookConfig::query()
            ->where('id', $configId)
            ->where('channel', 'app')
            ->first();

        if (! $config) {
            throw new CustomException('Widget config not found.', 404);
        }

        $config->update([
            'user_website_id' => $data['user_website_id'],
            'status' => 'active',
            'config' => [
                'domain_name' => $data['domain_name'],
            ],
        ]);

        return $config->refresh();
    }
}
