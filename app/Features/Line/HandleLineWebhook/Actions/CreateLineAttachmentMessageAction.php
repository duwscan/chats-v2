<?php

namespace App\Features\Line\HandleLineWebhook\Actions;

use App\Models\AttachmentModel;
use App\Models\ConversationModel;
use App\Models\MessageModel;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use LINE\Clients\MessagingApi\Api\MessagingApiBlobApi;
use LINE\Clients\MessagingApi\Configuration;
use LINE\Webhook\Model\ImageMessageContent;
use LINE\Webhook\Model\MessageEvent;

class CreateLineAttachmentMessageAction
{
    public function execute(
        ConversationModel $conversation,
        MessageModel $message,
        MessageEvent $event,
        string $accessToken,
    ): void {
        $lineMessage = $event->getMessage();

        if (! $lineMessage instanceof ImageMessageContent) {
            return;
        }

        if ($accessToken === '') {
            Log::warning('line.attachment.missing_access_token', [
                'message_id' => $lineMessage->getId(),
            ]);

            return;
        }

        try {
            $config = new Configuration;
            $config->setAccessToken($accessToken);
            $dataApi = new MessagingApiBlobApi(config: $config);

            $messageId = $lineMessage->getId();
            $imageRequest = $dataApi->getMessageContentRequest($messageId);

            $folder = 'messages/line/'.$conversation->id;
            $url = $imageRequest->getUri()->__toString();
            $headers = $imageRequest->getHeaders();

            $response = Http::withHeaders($headers)->get($url);

            if (! $response->successful()) {
                Log::warning('line.attachment.download_failed', [
                    'url' => $url,
                    'status' => $response->status(),
                ]);

                return;
            }

            $contents = $response->body();
            $mimeType = $response->header('Content-Type') ?? null;

            $extension = $mimeType && str_contains($mimeType, '/')
                ? explode('/', $mimeType, 2)[1]
                : 'bin';
            $filename = (string) Str::uuid().'.'.$extension;
            $path = $folder.'/'.$filename;

            Storage::disk('public')->put($path, $contents);

            AttachmentModel::query()->create([
                'conversation_id' => $conversation->id,
                'message_id' => $message->id,
                'uploader_id' => null,
                'filename' => $filename,
                'original_name' => $filename,
                'file_path' => $path,
                'file_size' => strlen($contents),
                'mime_type' => $mimeType,
                'file_extension' => $extension,
                'width' => null,
                'height' => null,
                'duration' => null,
                'thumbnail_path' => null,
                'preview_path' => null,
                'platform_file_id' => $lineMessage->getId(),
                'platform_type' => 'line',
                'upload_status' => 'local',
                's3_url' => null,
                'upload_started_at' => now(),
                'upload_completed_at' => now(),
                'upload_error' => null,
            ]);
        } catch (\Throwable $e) {
            Log::error('line.attachment.error', [
                'message_id' => $lineMessage->getId(),
                'error' => $e->getMessage(),
            ]);
        }
    }
}
