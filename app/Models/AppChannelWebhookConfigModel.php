<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Eloquent model for App-specific channel webhook configurations.
 *
 * This model stores a minimal configuration for the custom 'app' channel,
 * currently only a single property: domain_name.
 */
class AppChannelWebhookConfigModel extends Model
{
    /** @var string The database table name */
    protected $table = 'app_channel_webhook_configs';

    /** @var list<string> The attributes that are mass assignable */
    protected $fillable = [
        'domain_name',
    ];
}
