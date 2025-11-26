<?php

namespace Tests\Feature\Widget\UpdateAppAdapterChannel;

use App\Models\ChannelWebhookConfig;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateAppAdapterChannelTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_updates_widget_channel_config(): void
    {
        $config = ChannelWebhookConfig::create([
            'user_website_id' => 1,
            'channel' => 'app',
            'status' => 'pending',
            'config' => ['domain_name' => 'old.example.com'],
        ]);

        $payload = [
            'user_website_id' => 2,
            'domain_name' => 'new.example.com',
        ];

        $response = $this->putJson("/api/widget/channel/{$config->id}", $payload);

        $response->assertOk()
            ->assertJsonPath('_success', true)
            ->assertJsonPath('_data.id', $config->id)
            ->assertJsonPath('_data.user_website_id', '2')
            ->assertJsonPath('_data.channel', 'app')
            ->assertJsonPath('_data.status', 'active')
            ->assertJsonPath('_data.config.domain_name', 'new.example.com');

        $this->assertDatabaseHas('channel_webhook_configs', [
            'id' => $config->id,
            'user_website_id' => 2,
            'channel' => 'app',
            'status' => 'active',
        ]);
    }

    public function test_it_returns_not_found_when_widget_config_missing(): void
    {
        $payload = [
            'user_website_id' => 1,
            'domain_name' => 'example.com',
        ];

        $response = $this->putJson('/api/widget/channel/999', $payload);

        $response->assertStatus(404)
            ->assertJsonPath('_success', false);
    }
}
