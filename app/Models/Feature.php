<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Feature extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
    ];

    public function quota()
    {
        return $this->hasOneThrough(
            QuotaInterval::class,
            PlanFeature::class,
            'feature_id',
            'plan_feature_id',
            'id',
            'id'
        );
    }
}
