<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $customer_id
 * @property string $status
 * @property string|null $reason
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $status_changed_at
 * @property int|null $changed_by
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 */
class CustomerStatusModel extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'customer_statuses';

    protected $fillable = [
        'customer_id',
        'status',
        'reason',
        'notes',
        'status_changed_at',
        'changed_by',
    ];

    protected function casts(): array
    {
        return [
            'status_changed_at' => 'datetime',
        ];
    }

    protected static function newFactory()
    {
        return \Database\Factories\CustomerStatusFactory::new();
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(CustomerModel::class, 'customer_id');
    }

    public function changedBy(): BelongsTo
    {
        return $this->belongsTo(AgentModel::class, 'changed_by');
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isInactive(): bool
    {
        return $this->status === 'inactive';
    }

    public function isBlocked(): bool
    {
        return $this->status === 'blocked';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }
}
