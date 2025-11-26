<?php

namespace App\Features\Line\HandleLineWebhook;

use App\Exceptions\CustomException;
use App\Features\Line\LineChannel;
use App\Models\ChannelWebhookConfig;
use Illuminate\Http\Request;

class HandleLineWebhookAction
{
    public function execute(int $userWebsiteId, int $configId, Request $request): array
    {
        $config = ChannelWebhookConfig::query()
            ->where('user_website_id', $userWebsiteId)
            ->where('id', $configId)
            ->first();

        if (! $config) {
            throw new CustomException('LINE config not found.', 404);
        }

        $channelConfig = LineChannel::fromArray($config->config ?? []);
        $channelSecret = $channelConfig->getClientSecret();

        if (! $channelSecret) {
            throw new CustomException('LINE channel secret is missing.');
        }

        $signatureHeader = $request->header('X-Line-Signature');
        $body = $request->getContent();

        $computedSignature = base64_encode(hash_hmac('sha256', $body, $channelSecret, true));

        if (! hash_equals($computedSignature, (string) $signatureHeader)) {
            throw new CustomException('Invalid LINE signature.', 403);
        }

        return [
            'status' => 'ok',
        ];
    }
}
