<?php

namespace App\Features\Facebook\CreateFacebookChannelOnCallback;

use App\Exceptions\CustomException;
use App\Features\Facebook\CreateFacebookChannelOnCallback\FacebookPage as FacebookPageDto;
use App\Models\ChannelWebhookConfig;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CreateFacebookChannelAction
{
    public function __invoke(array $data): array
    {
        if (! empty($data['error'])) {
            $description = $data['error_description'] ?? (string) $data['error'];

            throw new CustomException($description);
        }

        $config = config('services.facebook');

        if (empty($config['app_id']) || empty($config['app_secret']) || empty($config['redirect'])) {
            throw new CustomException('Facebook app configuration is missing.');
        }

        $graphVersion = $config['graph_api_version'] ?? 'v15.0';
        $baseUrl = "https://graph.facebook.com/{$graphVersion}";

        $tokenResponse = Http::get("{$baseUrl}/oauth/access_token", [
            'client_id' => $config['app_id'],
            'client_secret' => $config['app_secret'],
            'redirect_uri' => $config['redirect'],
            'code' => $data['code'],
        ]);

        if ($tokenResponse->failed()) {
            Log::error('Facebook token exchange failed', [
                'status' => $tokenResponse->status(),
                'body' => $tokenResponse->body(),
            ]);

            throw new CustomException('Failed to exchange code for access token.');
        }

        $userAccessToken = $tokenResponse->json('access_token');

        if (! $userAccessToken) {
            throw new CustomException('Facebook did not return an access token.');
        }

        $pagesResponse = Http::get("{$baseUrl}/me/accounts", [
            'access_token' => $userAccessToken,
            'fields' => 'id,name,access_token,category,tasks',
        ]);

        if ($pagesResponse->failed()) {
            Log::error('Failed to fetch Facebook pages', [
                'status' => $pagesResponse->status(),
                'body' => $pagesResponse->body(),
            ]);

            throw new CustomException('Failed to fetch Facebook pages.');
        }

        $pages = collect($pagesResponse->json('data') ?? [])
            ->map(function (array $page): FacebookPageDto {
                return FacebookPageDto::fromArray([
                    'page_id' => $page['id'] ?? '',
                    'page_name' => $page['name'] ?? '',
                    'access_token' => $page['access_token'] ?? '',
                ]);
            })
            ->values()
            ->all();

        foreach ($pages as $page) {
            $pageId = $page->getPageId();

            if ($pageId === '') {
                continue;
            }

            ChannelWebhookConfig::query()->updateOrCreate(
                [
                    'user_website_id' => $data['state'],
                    'channel' => 'facebook',
                    'config->page_id' => $pageId,
                ],
                [
                    'config' => $page->toArray(),
                    'status' => 'active',
                ],
            );
        }

        return [
            'access_token' => $userAccessToken,
            'pages' => array_map(fn (FacebookPageDto $page) => $page->toArray(), $pages),
        ];
    }
}
