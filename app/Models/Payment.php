<?php

namespace App\Models;

use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_website_id',
        'user_id',
        'plan_id',
        'amount',
        'currency',
        'paid_at',
        'payment_code',
        'payment_method',
        'expired_at',
        'status',
        'type',
    ];

    protected function casts(): array
    {
        return [
            'paid_at' => 'datetime',
            'created_at' => 'datetime',
            'expired_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function userWebsite(): BelongsTo
    {
        return $this->belongsTo(UserWebsite::class);
    }

    public function isExpired(): bool
    {
        return $this->status === PaymentStatus::EXPRIED;
    }

    public function transactions() : HasMany{
        return $this->hasMany(Transaction::class);
    }
}
