<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class MessageModel extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'chat_messages';

    /**
     * Generate UUIDs using ordered UUIDs (UUIDv7-like).
     */
    public function uniqueIds(): array
    {
        return ['id'];
    }

    protected $fillable = [
        'conversation_id',
        'customer_id',
        'agent_id',
        'message_text',
        'message_type',
        'channel',
        'channel_conversation_id',
        'metadata',
        'thread_id',
        'reply_to_id',
        'read_at',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
            'read_at' => 'datetime',
        ];
    }

    protected static function newFactory()
    {
        return \Database\Factories\MessageFactory::new();
    }

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(ConversationModel::class, 'conversation_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(CustomerModel::class, 'customer_id');
    }

    public function agent(): BelongsTo
    {
        return $this->belongsTo(AgentModel::class, 'agent_id');
    }

    public function replyTo(): BelongsTo
    {
        return $this->belongsTo(MessageModel::class, 'reply_to_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(MessageModel::class, 'reply_to_id');
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(AttachmentModel::class, 'message_id');
    }

    public function isRead(): bool
    {
        return $this->read_at !== null;
    }

    public function isReply(): bool
    {
        return $this->reply_to_id !== null;
    }

    public function isInThread(): bool
    {
        return $this->thread_id !== null;
    }

    public function isFromCustomer(): bool
    {
        return $this->customer_id !== null && $this->agent_id === null;
    }

    public function isFromAgent(): bool
    {
        return $this->agent_id !== null && $this->customer_id === null;
    }

    public function markAsRead(): void
    {
        $this->update([
            'read_at' => now(),
        ]);
    }

    public function userWebsite(): BelongsTo
    {
        return $this->belongsTo(UserWebsite::class, 'user_website_id');
    }
}
