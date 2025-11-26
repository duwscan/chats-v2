<?php

namespace App\Features\Facebook\SendFacebookMessage\Text;

class FacebookTextMessage
{
    public function __construct(
        public readonly string $text,
        public readonly ?int $agentId = null,
        public readonly ?string $replyToId = null,
        public readonly ?int $conversationId = null,
        public readonly ?int $configId = null,
        public readonly ?int $userWebsiteId = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            text: $data['text'] ?? $data['message_text'] ?? '',
            agentId: $data['agent_id'] ?? null,
            replyToId: $data['reply_to_id'] ?? null,
            conversationId: $data['conversation_id'] ?? null,
            configId: $data['config_id'] ?? null,
            userWebsiteId: $data['user_website_id'] ?? null,
        );
    }
}
