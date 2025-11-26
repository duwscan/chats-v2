<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class FeatureUsage extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_website_id',
        'user_id',
        'quota_interval_id',
        'used_quota',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function quotaInterval(): BelongsTo
    {
        return $this->belongsTo(QuotaInterval::class);
    }
}
