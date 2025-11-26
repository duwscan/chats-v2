<?php

namespace App\Features\Facebook\HandleWebhook\Actions;

use App\Features\Facebook\HandleWebhook\FacebookWebhookMessage;

class ExtractFacebookMessageAction
{
    public function execute(array $message, string $pageId): FacebookWebhookMessage
    {
        return FacebookWebhookMessage::fromArray($message, $pageId);
    }
}
