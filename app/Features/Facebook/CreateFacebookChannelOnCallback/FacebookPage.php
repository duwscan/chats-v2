<?php

namespace App\Features\Facebook\CreateFacebookChannelOnCallback;

class FacebookPage
{
    private string $pageId;

    private string $pageName;

    private string $accessToken;

    public function __construct(string $pageId, string $pageName, string $accessToken)
    {
        $this->pageId = $pageId;
        $this->pageName = $pageName;
        $this->accessToken = $accessToken;
    }

    public function getPageId(): string
    {
        return $this->pageId;
    }

    public function getPageName(): string
    {
        return $this->pageName;
    }

    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['page_id'] ?? '',
            $data['page_name'] ?? '',
            $data['access_token'] ?? '',
        );
    }

    public function toArray(): array
    {
        return [
            'page_id' => $this->pageId,
            'page_name' => $this->pageName,
            'access_token' => $this->accessToken,
        ];
    }
}
