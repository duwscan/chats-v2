<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AgentModel extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'agents';

    protected $fillable = [
        'status',
        'last_activity_at',
        'type',
        'external_id',
        'info',
        'user_id',
        'user_website_id',
        'agent_code',
        'capabilities',
        'max_concurrent_chats',
    ];

    protected function casts(): array
    {
        return [
            'info' => 'array',
            'capabilities' => 'array',
            'last_activity_at' => 'datetime',
        ];
    }

    protected static function newFactory()
    {
        return \Database\Factories\AgentFactory::new();
    }

    public function conversations(): HasMany
    {
        return $this->hasMany(ConversationModel::class, 'assign_to');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(MessageModel::class, 'agent_id');
    }

    public function customerStatusChanges(): HasMany
    {
        return $this->hasMany(CustomerStatusModel::class, 'changed_by');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(AgentAssignmentModel::class, 'agent_id');
    }

    public function assignedConversations(): HasMany
    {
        return $this->hasMany(AgentAssignmentModel::class, 'assigned_by');
    }

    public function agentRequests(): HasMany
    {
        return $this->hasMany(AgentRequestHistoryModel::class, 'agent_id');
    }

    public function pendingRequests(): HasMany
    {
        return $this->hasMany(AgentRequestHistoryModel::class, 'agent_id')
            ->whereNull('responded_at')
            ->where('response_status', 'pending');
    }

    public function isOnline(): bool
    {
        return $this->status === 'online';
    }

    public function isAvailable(): bool
    {
        return in_array($this->status, ['online', 'available']);
    }

    // Notification relationships
    public function notifications()
    {
        return $this->morphMany(NotificationModel::class, 'notifiable');
    }

    public function notificationPreferences()
    {
        return $this->morphMany(NotificationPreferenceModel::class, 'notifiable');
    }

    // Helper methods for notifications
    public function unreadNotifications()
    {
        return $this->notifications()->unread();
    }

    public function getUnreadNotificationCount()
    {
        return $this->unreadNotifications()->count();
    }
}
