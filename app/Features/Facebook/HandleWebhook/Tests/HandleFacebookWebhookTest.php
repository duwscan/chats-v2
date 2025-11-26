<?php

namespace App\Features\Facebook\HandleWebhook\Tests;

use App\Models\AttachmentModel;
use App\Models\ChannelWebhookConfig;
use App\Models\MessageModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class HandleFacebookWebhookTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_image_message_with_attachment(): void
    {
        $this->withoutExceptionHandling();
        Storage::fake('public');
        Http::fake([
            'https://example.com/photo.jpg' => Http::response('fake-image', 200, ['Content-Type' => 'image/jpeg']),
        ]);

        config(['services.facebook.app_secret' => 'test-secret']);

        $config = ChannelWebhookConfig::query()->create([
            'user_website_id' => '1',
            'channel' => 'facebook',
            'status' => 'active',
            'config' => [
                'page_id' => 'PAGE_ID_123',
                'access_token' => 'page-token',
            ],
        ]);

        $payload = [
            'entry' => [
                [
                    'changes' => [
                        [
                            'value' => [
                                'page_id' => 'PAGE_ID_123',
                                'messages' => [
                                    [
                                        'mid' => 'MID123',
                                        'text' => 'hello image',
                                        'from' => ['id' => 'USER123'],
                                        'to' => ['data' => [['id' => 'PAGE_ID_123']]],
                                        'attachments' => [
                                            [
                                                'type' => 'image',
                                                'payload' => ['url' => 'https://example.com/photo.jpg'],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $rawBody = json_encode($payload);
        $signature = 'sha256='.hash_hmac('sha256', $rawBody, 'test-secret');

        $response = $this->withHeaders([
            'X-Hub-Signature-256' => $signature,
        ])->postJson('/api/webhook/facebook', $payload);

        $response->assertOk()
            ->assertJsonPath('_success', true)
            ->assertJsonPath('_data.processed', 1);

        $message = MessageModel::query()->first();
        $this->assertSame('image', $message->message_type);
        $this->assertSame('hello image', $message->message_text);

        $attachment = AttachmentModel::query()->first();
        $this->assertNotNull($attachment);
        $this->assertSame($message->id, $attachment->message_id);
        $this->assertSame('facebook', $attachment->platform_type);
        Storage::disk('public')->assertExists('messages/facebook/'.$attachment->conversation_id.'/'.$attachment->filename);
    }
}
