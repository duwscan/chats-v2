<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NotificationPreferenceModel extends Model
{
    use HasFactory;

    protected $table = 'notification_preferences';

    protected $fillable = [
        'notifiable_type',
        'notifiable_id',
        'notification_type',
        'channel',
        'is_enabled',
        'settings',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
        'settings' => 'array',
    ];

    // Polymorphic relationship
    public function notifiable()
    {
        return $this->morphTo();
    }

    // Scopes
    public function scopeEnabled($query)
    {
        return $query->where('is_enabled', true);
    }

    public function scopeForNotifiable($query, $notifiableType, $notifiableId)
    {
        return $query->where('notifiable_type', $notifiableType)
                    ->where('notifiable_id', $notifiableId);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('notification_type', $type);
    }

    public function scopeByChannel($query, $channel)
    {
        return $query->where('channel', $channel);
    }

    // Helper methods
    public function isEnabledForChannel($channel)
    {
        return $this->is_enabled && $this->channel === $channel;
    }

    public function getSetting($key, $default = null)
    {
        return $this->settings[$key] ?? $default;
    }

    public function setSetting($key, $value)
    {
        $settings = $this->settings ?? [];
        $settings[$key] = $value;
        $this->settings = $settings;
        return $this;
    }
}
