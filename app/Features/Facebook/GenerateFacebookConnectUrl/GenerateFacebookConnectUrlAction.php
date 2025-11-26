<?php

namespace App\Features\Facebook\GenerateFacebookConnectUrl;

use App\Exceptions\CustomException;

class GenerateFacebookConnectUrlAction
{
    public function __invoke(int $userWebsiteId): string
    {
        $config = config('services.facebook');

        if (empty($config['app_id']) || empty($config['redirect'])) {
            throw new CustomException('Facebook app id or redirect is not configured.');
        }

        $scopes = implode(',', $config['scopes'] ?? []);

        $params = [
            'client_id' => $config['app_id'],
            'redirect_uri' => $config['redirect'],
            'scope' => $scopes,
            'response_type' => 'code',
            'state' => (string) $userWebsiteId,
        ];

        $baseUrl = $config['dialog_oauth'] ?? 'https://www.facebook.com/v22.0/dialog/oauth';

        return $baseUrl.'?'.http_build_query($params);
    }
}
