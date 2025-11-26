<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConversationModel extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'conversations';

    protected $fillable = [
        'customer_id',
        'assign_to',
        'settings',
        'status',
        'last_message_at',
        'message_count',
        'assignment_metadata',
        'channel',
    ];

    protected function casts(): array
    {
        return [
            'settings' => 'array',
            'assignment_metadata' => 'array',
            'last_message_at' => 'datetime',
        ];
    }

    protected static function newFactory()
    {
        return \Database\Factories\ConversationFactory::new();
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(CustomerModel::class, 'customer_id');
    }

    public function agent(): BelongsTo
    {
        return $this->belongsTo(AgentModel::class, 'assign_to');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(MessageModel::class, 'conversation_id');
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(AttachmentModel::class, 'conversation_id');
    }

    public function lastMessage(): HasOne
    {
        return $this->hasOne(MessageModel::class, 'conversation_id')
            ->latestOfMany()
            ->with(['customer', 'agent', 'attachments']);
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(AgentAssignmentModel::class, 'conversation_id');
    }

    public function agentRequests(): HasMany
    {
        return $this->hasMany(AgentRequestHistoryModel::class, 'conversation_id');
    }

    public function pendingAgentRequests(): HasMany
    {
        return $this->hasMany(AgentRequestHistoryModel::class, 'conversation_id')
            ->whereNull('responded_at')
            ->where('response_status', 'pending');
    }

    public function latestAgentRequest()
    {
        return $this->hasOne(AgentRequestHistoryModel::class, 'conversation_id')
            ->latestOfMany();
    }

    public function isAssigned(): bool
    {
        return $this->assign_to !== null;
    }

    public function isOpen(): bool
    {
        return $this->status === 'open';
    }

    public function isInProgress(): bool
    {
        return $this->status === 'in_progress';
    }

    public function isClosed(): bool
    {
        return in_array($this->status, ['resolved', 'closed']);
    }
}
