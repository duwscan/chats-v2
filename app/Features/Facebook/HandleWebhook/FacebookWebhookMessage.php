<?php

namespace App\Features\Facebook\HandleWebhook;

use App\Exceptions\CustomException;

class FacebookWebhookMessage
{
    public function __construct(
        public readonly string $senderId,
        public readonly string $recipientId,
        public readonly string $mid,
        public readonly string $messageType,
        public readonly ?string $text,
        public readonly bool $isPageEcho,
        public readonly string $customerExternalId,
        public readonly array $attachments = [],
        public readonly array $rawMessage = [],
    ) {}

    public static function fromArray(array $message, string $pageId): self
    {
        $sender = (string) ($message['sender']['id'] ?? '');
        $recipient = (string) ($message['recipient']['id'] ?? '');

        if ($sender === '' || $recipient === '') {
            throw new CustomException('Invalid Facebook webhook payload: missing sender or recipient.');
        }

        $body = $message['message'] ?? [];
        $mid = (string) ($body['mid'] ?? '');

        if ($mid === '') {
            throw new CustomException('Invalid Facebook webhook payload: missing message ID.');
        }

        $text = $body['text'] ?? null;
        $attachments = $body['attachments'] ?? [];

        $type = 'unknown';

        if (! empty($text)) {
            $type = 'text';
        }

        if (! empty($attachments)) {
            $type = 'attachment';
        }

        $isPageEcho = $sender === $pageId || (($body['is_echo'] ?? false) === true);
        $customerExternalId = $isPageEcho ? $recipient : $sender;

        return new self(
            senderId: $sender,
            recipientId: $recipient,
            mid: $mid,
            messageType: $type,
            text: $text,
            isPageEcho: $isPageEcho,
            customerExternalId: $customerExternalId,
            attachments: is_array($attachments) ? $attachments : [],
            rawMessage: $message,
        );
    }

    public function isText(): bool
    {
        return $this->messageType === 'text';
    }

    public function isAttachment(): bool
    {
        return $this->messageType === 'attachment';
    }

    public function toArray(): array
    {
        return [
            'sender_id' => $this->senderId,
            'recipient_id' => $this->recipientId,
            'mid' => $this->mid,
            'message_type' => $this->messageType,
            'text' => $this->text,
            'is_page_echo' => $this->isPageEcho,
            'customer_external_id' => $this->customerExternalId,
        ];
    }
}
