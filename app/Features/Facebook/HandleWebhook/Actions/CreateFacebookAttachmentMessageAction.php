<?php

namespace App\Features\Facebook\HandleWebhook\Actions;

use App\Features\Facebook\HandleWebhook\FacebookWebhookMessage;
use App\Models\AttachmentModel;
use App\Models\ConversationModel;
use App\Models\MessageModel;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CreateFacebookAttachmentMessageAction
{
    public function execute(
        ConversationModel $conversation,
        MessageModel $message,
        FacebookWebhookMessage $facebookMessage,
    ): void {
        $attachments = $facebookMessage->attachments;

        if ($attachments === []) {
            return;
        }

        foreach ($attachments as $att) {
            try {
                $payload = $att['payload'] ?? [];
                $remoteUrl = isset($payload['url']) ? (string) $payload['url'] : null;

                if (! $remoteUrl) {
                    Log::warning('facebook.attachment.no_url', [
                        'attachment' => $att,
                    ]);

                    continue;
                }

                $response = Http::get($remoteUrl);

                if (! $response->successful()) {
                    Log::warning('facebook.attachment.download_failed', [
                        'url' => $remoteUrl,
                        'status' => $response->status(),
                    ]);

                    continue;
                }

                $contents = $response->body();
                $mimeType = $response->header('Content-Type') ?? null;

                $folder = 'messages/facebook/'.$conversation->id;
                $extension = pathinfo(parse_url($remoteUrl, PHP_URL_PATH) ?? '', PATHINFO_EXTENSION) ?: 'bin';
                $filename = Str::uuid().'.'.$extension;
                $path = $folder.'/'.$filename;

                Storage::disk('public')->put($path, $contents);

                AttachmentModel::query()->create([
                    'conversation_id' => $conversation->id,
                    'message_id' => $message->id,
                    'uploader_id' => null,
                    'filename' => $filename,
                    'original_name' => basename(parse_url($remoteUrl, PHP_URL_PATH) ?? $filename),
                    'file_path' => $path,
                    'file_size' => strlen($contents),
                    'mime_type' => $mimeType,
                    'file_extension' => $extension,
                    'width' => null,
                    'height' => null,
                    'duration' => null,
                    'thumbnail_path' => null,
                    'preview_path' => null,
                    'platform_file_id' => $facebookMessage->mid,
                    'platform_type' => 'facebook',
                    'upload_status' => 'local',
                    's3_url' => null,
                    'upload_started_at' => now(),
                    'upload_completed_at' => now(),
                    'upload_error' => null,
                ]);
            } catch (\Throwable $e) {
                Log::error('facebook.attachment.error', [
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }
}
