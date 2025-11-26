<?php

namespace App\Features\Line\HandleLineWebhook\Actions;

use App\Features\Line\HandleLineWebhook\LineWebhookEvent;
use App\Models\CustomerModel;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class UpsertLineCustomerAction
{
    public function execute(LineWebhookEvent $event, int $userWebsiteId, string $accessToken): CustomerModel
    {
        $customer = CustomerModel::query()->firstOrCreate(
            [
                'channel' => 'line',
                'channel_user_id' => $event->userId,
                'user_website_id' => $userWebsiteId,
            ],
            [
                'display_name' => $event->userId,
                'last_activity_at' => now(),
            ],
        );

        if (! $customer->wasRecentlyCreated) {
            $customer->update([
                'last_activity_at' => now(),
            ]);

            return $customer;
        }

        $profile = $this->fetchProfile($event->userId, $accessToken);
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

        $response = Http::withToken($accessToken)
            ->acceptJson()
            ->get("https://api.line.me/v2/bot/profile/{$userId}");

        if ($response->successful()) {
            $data = $response->json() ?? [];

            return [
                'display_name' => $data['displayName'] ?? null,
                'avatar_url' => $data['pictureUrl'] ?? null,
            ];
        }

        Log::warning('line.profile.fetch_failed', [
            'user_id' => $userId,
            'status' => $response->status(),
        ]);

        return [];
    }
}
