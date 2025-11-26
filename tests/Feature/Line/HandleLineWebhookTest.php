<?php

namespace Tests\Feature\Line;

use App\Models\ChannelWebhookConfig;
use App\Models\ConversationModel;
use App\Models\MessageModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class HandleLineWebhookTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_message_from_line_text_event(): void
    {
        Http::fake([
            'https://api.line.me/v2/bot/profile/*' => Http::response([
                'displayName' => 'Line User',
                'pictureUrl' => 'https://example.test/avatar.png',
            ], 200, ['Content-Type' => 'application/json']),
        ]);

        $channelSecret = 'line-secret';
        $accessToken = 'line-access-token';
        $userWebsiteId = 1;

        $config = ChannelWebhookConfig::query()->create([
            'user_website_id' => $userWebsiteId,
            'channel' => 'line',
            'status' => 'active',
            'config' => [
                'client_id' => 'line-client-id',
                'client_secret' => $channelSecret,
                'access_token' => $accessToken,
                'channel_name' => 'Test LINE',
            ],
        ]);

        $payload = [
            'destination' => 'line-bot',
            'events' => [
                [
                    'type' => 'message',
                    'replyToken' => 'reply-token-123',
                    'source' => [
                        'type' => 'user',
                        'userId' => 'line-user-123',
                    ],
                    'timestamp' => 1732600000000,
                    'message' => [
                        'id' => 'msg-line-123',
                        'type' => 'text',
                        'text' => 'Hello from LINE',
                    ],
                ],
            ],
        ];

        $body = json_encode($payload);
        $signature = base64_encode(hash_hmac('sha256', $body, $channelSecret, true));

        $response = $this->withHeaders([
            'X-Line-Signature' => $signature,
        ])->postJson("/api/webhook/line/{$userWebsiteId}/{$config->id}", $payload);

        $response->assertOk()
            ->assertJsonPath('_data.status', 'ok')
            ->assertJsonPath('_data.processed', 1);

        $this->assertDatabaseHas('customers', [
            'channel' => 'line',
            'channel_user_id' => 'line-user-123',
            'user_website_id' => (string) $userWebsiteId,
            'display_name' => 'line-user-123', // Falls back to userId when profile fetch fails in test
            'channel_webhook_config_id' => $config->id,
        ]);

        $conversation = ConversationModel::query()->first();
        $this->assertNotNull($conversation);
        $this->assertSame('line', $conversation->channel);
        $this->assertSame('open', $conversation->status);

        $message = MessageModel::query()->first();
        $this->assertNotNull($message);
        $this->assertSame('Hello from LINE', $message->message_text);
        $this->assertSame('text', $message->message_type);
        $this->assertSame('line', $message->channel);
        $this->assertSame('line-user-123', $message->channel_conversation_id);
        $this->assertSame('msg-line-123', $message->thread_id);
        $this->assertSame('reply-token-123', $message->metadata['reply_token'] ?? null);
        $this->assertSame('text', $message->metadata['line_message_type'] ?? null);
        $this->assertSame($config->id, $message->metadata['config_id'] ?? null);
    }
}
