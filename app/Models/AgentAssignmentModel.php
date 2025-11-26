<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class AgentAssignmentModel extends Model
{
    use HasFactory;

    protected $table = 'agent_assignments';

    protected $fillable = [
        'customer_id',
        'conversation_id',
        'agent_id',
        'assignment_type',
        'status',
        'assigned_at',
        'completed_at',
        'action_at',
        'assigned_by',
        'transferred_to',
        'performed_by',
        'notes',
        'reason',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'assigned_at' => 'datetime',
            'completed_at' => 'datetime',
            'action_at' => 'datetime',
            'metadata' => 'array',
        ];
    }

    // Relationships
    public function customer(): BelongsTo
    {
        return $this->belongsTo(CustomerModel::class, 'customer_id');
    }

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(ConversationModel::class, 'conversation_id');
    }

    public function agent(): BelongsTo
    {
        return $this->belongsTo(AgentModel::class, 'agent_id');
    }

    public function assignedBy(): BelongsTo
    {
        return $this->belongsTo(AgentModel::class, 'assigned_by');
    }

    public function transferredTo(): BelongsTo
    {
        return $this->belongsTo(AgentModel::class, 'transferred_to');
    }

    public function performedBy(): BelongsTo
    {
        return $this->belongsTo(AgentModel::class, 'performed_by');
    }

    // Scopes
    public function scopeCurrent(Builder $query): Builder
    {
        return $query->whereIn('status', ['active', 'completed', 'transferred', 'cancelled']);
    }

    public function scopeHistory(Builder $query): Builder
    {
        return $query->where('status', 'like', 'history_%');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    public function scopeByAgent(Builder $query, int $agentId): Builder
    {
        return $query->where('agent_id', $agentId);
    }

    public function scopeByConversation(Builder $query, int $conversationId): Builder
    {
        return $query->where('conversation_id', $conversationId);
    }

    // Helper methods
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isTransferred(): bool
    {
        return $this->status === 'transferred';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    public function isHistory(): bool
    {
        return str_starts_with($this->status, 'history_');
    }

    public function isPrimary(): bool
    {
        return $this->assignment_type === 'primary';
    }

    public function isSecondary(): bool
    {
        return $this->assignment_type === 'secondary';
    }

    public function isObserver(): bool
    {
        return $this->assignment_type === 'observer';
    }
}
