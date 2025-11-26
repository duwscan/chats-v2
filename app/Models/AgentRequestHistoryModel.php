<?php

namespace App\Models;

use App\Enums\AgentRequestResponseStatus;
use App\Enums\AgentRequestType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class AgentRequestHistoryModel extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'agent_request_histories';

    protected $fillable = [
        'conversation_id',
        'user_website_id',
        'agent_id',
        'requested_at',
        'responded_at',
        'response_status',
        'request_type',
        'reject_actor_type',
        'reject_actor_id',
        'response_note',
    ];

    protected $casts = [
        'requested_at' => 'datetime',
        'responded_at' => 'datetime',
        'request_type' => AgentRequestType::class,
        'response_status' => AgentRequestResponseStatus::class,
    ];

    /**
     * Get the conversation associated with this request
     */
    public function conversation(): BelongsTo
    {
        return $this->belongsTo(ConversationModel::class, 'conversation_id');
    }

    /**
     * Get the agent associated with this request
     */
    public function agent(): BelongsTo
    {
        return $this->belongsTo(AgentModel::class, 'agent_id');
    }

    /**
     * Get the actor who rejected this request (polymorphic)
     */
    public function rejectActor(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Scope a query to only include records for a specific conversation
     */
    public function scopeForConversation($query, $conversationId)
    {
        return $query->where('conversation_id', $conversationId);
    }

    /**
     * Scope a query to only include records for a specific agent
     */
    public function scopeForAgent($query, $agentId)
    {
        return $query->where('agent_id', $agentId);
    }

    /**
     * Scope a query to only include records for a specific user website
     */
    public function scopeForUserWebsite($query, $userWebsiteId)
    {
        return $query->where('user_website_id', $userWebsiteId);
    }

    /**
     * Scope a query to only include pending requests (not responded yet)
     */
    public function scopePending($query)
    {
        return $query->whereNull('responded_at')
            ->where('response_status', AgentRequestResponseStatus::PENDING->value);
    }

    /**
     * Scope a query to only include accepted requests
     */
    public function scopeAccepted($query)
    {
        return $query->where('response_status', AgentRequestResponseStatus::ACCEPTED->value);
    }

    /**
     * Scope a query to only include rejected requests
     */
    public function scopeRejected($query)
    {
        return $query->where('response_status', AgentRequestResponseStatus::REJECTED->value);
    }

    /**
     * Check if request is pending
     */
    public function isPending(): bool
    {
        return $this->response_status === AgentRequestResponseStatus::PENDING && $this->responded_at === null;
    }

    /**
     * Check if request is accepted
     */
    public function isAccepted(): bool
    {
        return $this->response_status === AgentRequestResponseStatus::ACCEPTED;
    }

    /**
     * Check if request is rejected
     */
    public function isRejected(): bool
    {
        return $this->response_status === AgentRequestResponseStatus::REJECTED;
    }
}
