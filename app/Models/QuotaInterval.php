<?php

namespace App\Models;

use App\Builder\MutationQuery;
use App\Builder\QuotaIntervalBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuotaInterval extends Model
{
    use SoftDeletes ;

    protected $fillable = [
        'plan_feature_id',
        'quota',
        'quota_per_second',
        'quota_per_minute',
        'quota_per_day',
    ];

    public function planFeature(): BelongsTo
    {
        return $this->belongsTo(PlanFeature::class, 'plan_feature_id', 'id');
    }

    public function newEloquentBuilder($query): QuotaIntervalBuilder
    {
        return new QuotaIntervalBuilder($query);
    }
}
