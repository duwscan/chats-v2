<?php

namespace App\Features\Facebook\HandleWebhook\Actions;

use App\Features\Facebook\HandleWebhook\FacebookWebhookEvent;
use App\Models\AttachmentModel;
use App\Models\ConversationModel;
use App\Models\CustomerModel;
use App\Models\MessageModel;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

class CreateFacebookMessageAction
{
    public function execute(ConversationModel $conversation, CustomerModel $customer, FacebookWebhookEvent $event): void
    {
        $messageText = $event->text ?? '';
        $messageType = $this->hasImageAttachment($event->attachments) ? 'image' : 'text';
        $message = MessageModel::query()->create([
            'conversation_id' => $conversation->id,
            'customer_id' => $customer->id,
            'message_text' => $messageText,
            'message_type' => $messageType,
            'channel' => 'facebook',
            'channel_conversation_id' => $event->recipientId,
            'thread_id' => $event->mid,
            'metadata' => [
                'page_id' => $event->pageId,
                'attachments' => $event->attachments,
            ],
        ]);

        $this->storeImageAttachments($conversation, $message, $event);
    }

    private function hasImageAttachment(array $attachments): bool
    {
        foreach ($attachments as $attachment) {
            if (($attachment['type'] ?? null) === 'image') {
                return true;
            }
        }

        return false;
    }

    private function storeImageAttachments(ConversationModel $conversation, MessageModel $message, FacebookWebhookEvent $event): void
    {
        foreach ($event->attachments as $attachment) {
            if (($attachment['type'] ?? null) !== 'image') {
                continue;
            }

            $payload = $attachment['payload'] ?? [];
            $remoteUrl = is_array($payload) ? ($payload['url'] ?? null) : null;

            if (! $remoteUrl) {
                continue;
            }

            try {
                $response = Http::get($remoteUrl);

                if (! $response->successful()) {
                    Log::warning('facebook.webhook.image_download_failed', [
                        'url' => $remoteUrl,
                        'status' => $response->status(),
                    ]);

                    continue;
                }

                $contents = $response->body();
                $mimeType = (string) ($response->header('Content-Type') ?? 'image/jpeg');
                $extension = $this->guessExtension($remoteUrl, $mimeType);
                $filename = $this->buildFilename($extension);
                $path = 'messages/facebook/'.$conversation->id.'/'.$filename;

                Storage::disk('public')->put($path, $contents);

                AttachmentModel::query()->create([
                    'conversation_id' => $conversation->id,
                    'message_id' => $message->id,
                    'filename' => $filename,
                    'original_name' => $this->extractOriginalName($remoteUrl) ?? $filename,
                    'file_path' => Storage::disk('public')->url($path),
                    'file_size' => strlen($contents),
                    'mime_type' => $mimeType,
                    'file_extension' => $extension,
                    'platform_file_id' => $event->mid,
                    'platform_type' => 'facebook',
                ]);
            } catch (Throwable $exception) {
                Log::warning('facebook.webhook.image_store_failed', [
                    'url' => $remoteUrl,
                    'error' => $exception->getMessage(),
                ]);
            }
        }
    }

    private function guessExtension(?string $remoteUrl, ?string $mimeType): ?string
    {
        $path = parse_url((string) $remoteUrl, PHP_URL_PATH);
        $extension = $path ? pathinfo($path, PATHINFO_EXTENSION) : null;

        if ($extension) {
            return strtolower($extension);
        }

        if ($mimeType && str_contains($mimeType, '/')) {
            return strtolower(explode('/', $mimeType)[1]);
        }

        return null;
    }

    private function buildFilename(?string $extension): string
    {
        return $extension ? (string) Str::uuid().'.'.$extension : (string) Str::uuid();
    }

    private function extractOriginalName(?string $remoteUrl): ?string
    {
        $path = parse_url((string) $remoteUrl, PHP_URL_PATH);

        return $path ? basename($path) : null;
    }
}
