<?php

namespace App\Features\Line\HandleLineWebhook\Actions;

use App\Models\CustomerModel;
use Illuminate\Support\Facades\Log;
use LINE\Clients\MessagingApi\Api\MessagingApiApi;
use LINE\Clients\MessagingApi\Configuration;
use LINE\Webhook\Model\MessageEvent;

class UpsertLineCustomerAction
{
    public function execute(MessageEvent $event, int $userWebsiteId, string $accessToken): CustomerModel
    {
        $source = $event->getSource();
        $userId = $source->getUserId();

        $customer = CustomerModel::query()->firstOrCreate(
            [
                'channel' => 'line',
                'channel_user_id' => $userId,
                'user_website_id' => $userWebsiteId,
            ],
            [
                'display_name' => $userId,
                'last_activity_at' => now(),
            ],
        );

        if (! $customer->wasRecentlyCreated) {
            $customer->update([
                'last_activity_at' => now(),
            ]);

            return $customer;
        }

        $profile = $this->fetchProfile($userId, $accessToken);
        $customer->update(array_filter([
            'display_name' => $profile['display_name'] ?? $customer->display_name,
            'avatar_url' => $profile['avatar_url'] ?? null,
        ], fn ($value) => $value !== null));

        return $customer;
    }

    private function fetchProfile(string $userId, string $accessToken): array
    {
        if ($accessToken === '') {
            return [];
        }

        try {
            $config = new Configuration;
            $config->setAccessToken($accessToken);
            $messagingApi = new MessagingApiApi(config: $config);

            $userProfile = $messagingApi->getProfile($userId);

            return [
                'display_name' => $userProfile->getDisplayName(),
                'avatar_url' => $userProfile->getPictureUrl(),
            ];
        } catch (\Throwable $e) {
            Log::warning('line.profile.fetch_failed', [
                'user_id' => $userId,
                'error' => $e->getMessage(),
            ]);

            return [];
        }
    }
}
