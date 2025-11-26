<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChannelWebhookConfig extends Model
{
    protected $table = 'channel_webhook_configs';

    protected $fillable = [
        'id',
        'user_website_id',
        'channel',
        'status',
        'config',
        'config->page_id',
    ];

    protected $casts = [
        'status' => 'string',
        'config' => 'array',
    ];
}
