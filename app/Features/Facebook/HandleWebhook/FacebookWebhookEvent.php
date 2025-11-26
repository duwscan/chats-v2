<?php

namespace App\Features\Facebook\HandleWebhook;

readonly class FacebookWebhookEvent
{
    public function __construct(
        public string $pageId,
        public string $senderId,
        public string $recipientId,
        public ?string $mid,
        public ?string $text,
        public array $attachments = [],
    ) {}

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
