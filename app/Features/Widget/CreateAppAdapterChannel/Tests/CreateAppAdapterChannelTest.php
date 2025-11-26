<?php

namespace Tests\Feature\Widget\CreateAppAdapterChannel;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateAppAdapterChannelTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_widget_channel_config(): void
    {
        $payload = [
            'user_website_id' => 1,
            'domain_name' => 'example.com',
        ];

        $response = $this->postJson('/api/widget/channel', $payload);

        $response->assertOk()
            ->assertJsonPath('_success', true)
            ->assertJsonPath('_data.channel', 'app')
            ->assertJsonPath('_data.config.domain_name', 'example.com')
            ->assertJsonPath('_data.user_website_id', 1);

        $this->assertDatabaseHas('channel_webhook_configs', [
            'user_website_id' => 1,
            'channel' => 'app',
            'status' => 'active',
        ]);
    }

    public function test_it_validates_required_fields(): void
    {
        $response = $this->postJson('/api/widget/channel', []);

        $response->assertStatus(422)
            ->assertJsonPath('_success', false)
            ->assertJsonPath('_data.errors.user_website_id.0', 'The user website id field is required.')
            ->assertJsonPath('_data.errors.domain_name.0', 'The domain name field is required.');
    }
}
