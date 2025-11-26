<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class PlanFeature extends Model
{
    use SoftDeletes;

    protected $table = 'plan_features';

    protected $fillable = [
        'plan_id',
        'feature_id',
    ];

    public function feature()
    {
        return $this->belongsTo(Feature::class, 'feature_id', 'id');
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class, 'plan_id', 'id');
    }

    public function quotaInterval(): HasOne
    {
        return $this->hasOne(QuotaInterval::class, 'plan_feature_id', 'id');
    }
}
