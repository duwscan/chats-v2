<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Agent extends Model
{
    use HasFactory;

    protected $fillable = [
        'status',
        'last_activity_at',
        'type',
        'external_id',
        'info',
    ];

    protected function casts(): array
    {
        return [
            'info' => 'array',
            'last_activity_at' => 'datetime',
        ];
    }

    public function conversations(): HasMany
    {
        return $this->hasMany(ConversationModel::class, 'assign_to');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(MessageModel::class, 'agent_id');
    }

    public function isOnline(): bool
    {
        return $this->status === 'online';
    }

    public function isAvailable(): bool
    {
        return in_array($this->status, ['online', 'available']);
    }
}
