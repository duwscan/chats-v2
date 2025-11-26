<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NotificationTemplateModel extends Model
{
    use HasFactory;

    protected $table = 'notification_templates';

    protected $fillable = [
        'name',
        'type',
        'channel',
        'title',
        'content',
        'variables',
        'priority',
        'is_active',
        'metadata',
    ];

    protected $casts = [
        'variables' => 'array',
        'metadata' => 'array',
        'is_active' => 'boolean',
    ];

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByChannel($query, $channel)
    {
        return $query->where('channel', $channel);
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    // Helper methods
    public function processTemplate($variables = [])
    {
        $title = $this->title;
        $content = $this->content;

        foreach ($variables as $key => $value) {
            $placeholder = '{' . $key . '}';
            $title = str_replace($placeholder, $value, $title);
            $content = str_replace($placeholder, $value, $content);
        }

        return [
            'title' => $title,
            'content' => $content,
        ];
    }

    public function isHighPriority()
    {
        return in_array($this->priority, ['high', 'urgent']);
    }

    public function isUrgent()
    {
        return $this->priority === 'urgent';
    }
}
