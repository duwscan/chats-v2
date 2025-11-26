<?php

namespace App\Features\Line\SendLineMessage\Text;

class SendLineTextMessageData
{
    public function __construct(
        public readonly string $text,
        public readonly int $configId,
        public readonly ?string $replyToken = null,
        public readonly ?int $conversationId = null,
        public readonly ?int $agentId = null,
        public readonly ?string $replyToId = null,
    ) {}
}
