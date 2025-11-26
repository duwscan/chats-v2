<?php

namespace App\Features\Line\HandleLineWebhook;

use App\Exceptions\CustomException;

class LineWebhookEvent
{
    public function __construct(
        public readonly string $userId,
        public readonly string $messageId,
        public readonly string $messageType,
        public readonly ?string $text,
        public readonly ?string $replyToken,
        public readonly array $rawMessage = [],
    ) {}

    public static function fromArray(array $event): ?self
    {
        if (($event['type'] ?? null) !== 'message') {
            return null;
        }

        $source = $event['source'] ?? [];

        if (($source['type'] ?? null) !== 'user') {
            throw new CustomException('Only LINE user messages are supported.');
        }

        $message = $event['message'] ?? [];
        $messageType = $message['type'] ?? null;
        $messageId = $message['id'] ?? null;
        $userId = $source['userId'] ?? null;

        if (! $messageType || ! $messageId || ! $userId) {
            throw new CustomException('Invalid LINE webhook payload.');
        }

        if (! in_array($messageType, ['text', 'image'], true)) {
            throw new CustomException('Unsupported LINE message type: '.$messageType);
        }

        return new self(
            userId: (string) $userId,
            messageId: (string) $messageId,
            messageType: (string) $messageType,
            text: $message['text'] ?? null,
            replyToken: $event['replyToken'] ?? null,
            rawMessage: $message,
        );
    }

    public function isText(): bool
    {
        return $this->messageType === 'text';
    }

    public function isImage(): bool
    {
        return $this->messageType === 'image';
    }

    public function toArray(): array
    {
        return [
            'user_id' => $this->userId,
            'message_id' => $this->messageId,
            'message_type' => $this->messageType,
            'text' => $this->text,
        ];
    }
}
