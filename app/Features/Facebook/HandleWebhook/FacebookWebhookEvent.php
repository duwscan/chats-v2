<?php

namespace App\Features\Facebook\HandleWebhook;

class FacebookWebhookEvent
{
    public function __construct(
        public readonly string $pageId,
        public readonly string $senderId,
        public readonly string $recipientId,
        public readonly ?string $mid,
        public readonly ?string $text,
        public readonly array $attachments = [],
    ) {
    }

    public static function fromArray(array $entry): self
    {
        $changes = $entry['changes'][0] ?? null;
        $value = $changes['value'] ?? null;
        $message = $value['messages'][0] ?? [];

        return new self(
            pageId: $value['page_id'] ?? '',
            senderId: $message['from']['id'] ?? '',
            recipientId: $message['to']['data'][0]['id'] ?? '',
            mid: $message['mid'] ?? null,
            text: $message['text'] ?? null,
            attachments: $message['attachments'] ?? [],
        );
    }

    public function toArray(): array
    {
        return [
            'page_id' => $this->pageId,
            'sender_id' => $this->senderId,
            'recipient_id' => $this->recipientId,
            'mid' => $this->mid,
            'text' => $this->text,
            'attachments' => $this->attachments,
        ];
    }
}
