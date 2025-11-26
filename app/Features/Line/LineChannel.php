<?php

namespace App\Features\Line;

use InvalidArgumentException;

class LineChannel
{
    private string $client_id;
    private string $client_secret;
    private string $access_token;
    private ?string $channel_name;

    public function __construct(string $client_id, string $client_secret, string $access_token, ?string $channel_name = null)
    {
        $this->client_id = $client_id;
        $this->client_secret = $client_secret;
        $this->access_token = $access_token;
        $this->channel_name = $channel_name;
    }

    /**
     * Create a LineChannel from an array.
     * Required keys: client_id, client_secret, access_token
     * Optional: channel_name
     *
     * @param  array  $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        $required = ['client_id', 'client_secret', 'access_token'];

        foreach ($required as $key) {
            if (!isset($data[$key]) || $data[$key] === '') {
                throw new InvalidArgumentException(sprintf('Missing required key "%s" when creating %s', $key, self::class));
            }
        }

        return new self(
            (string) $data['client_id'],
            (string) $data['client_secret'],
            (string) $data['access_token'],
            isset($data['channel_name']) ? (string) $data['channel_name'] : null
        );
    }

    public function getClientId(): string
    {
        return $this->client_id;
    }

    public function getClientSecret(): string
    {
        return $this->client_secret;
    }

    public function getAccessToken(): string
    {
        return $this->access_token;
    }

    public function getChannelName(): ?string
    {
        return $this->channel_name;
    }

    public function toArray(): array
    {
        return [
            'client_id' => $this->client_id,
            'client_secret' => $this->client_secret,
            'access_token' => $this->access_token,
            'channel_name' => $this->channel_name,
        ];
    }
}
