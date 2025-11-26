<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserWebsite extends Model implements AuthorizableContract
{
    use Authorizable, HasFactory;

    protected $fillable = [
        'user_id',
        'plan_id',
        'domain_name',
        'domain_protocol', // default https
        'name',
        'domain',
        'description',
        'settings',
        'activated_at',
        'activated_key',
        'activated_notified_at',
    ];

    protected function casts(): array
    {
        return [
            'settings' => 'array',
            'activated_notified_at' => 'datetime',
        ];
    }

    public function customers(): HasMany
    {
        return $this->hasMany(CustomerModel::class, 'user_website_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function featureUsages(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(FeatureUsage::class);
    }

    public function hasFeature(string $feature): bool
    {
        return $this->plan()->withWhereHas('features',
            function (\Illuminate\Database\Eloquent\Builder $builder) use ($feature) {
                $builder->where('name', $feature);
            })->exists();
    }

    public function getFeatureUsage(
        string $feature
    ): FeatureUsage
    {
        return $this->featureUsages()->whereHas('quotaInterval.planFeature.feature', function ($query) use ($feature) {
            $query->where('name', $feature);
        })->firstOrFail();
    }

    public function planFeaturesQuotaInterval(
        string $feature
    )
    {
        return $this->featureUsages()->whereHas('quotaInterval.planFeature.feature', function ($query) use ($feature) {
            $query->where('name', $feature);
        })->firstOrFail()->quotaInterval()->firstOrFail();
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function latestSubscription(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Subscription::class)->latestOfMany();
    }

    public function plans()
    {
        return $this->hasManyThrough(Plan::class, Subscription::class, 'user_website_id', 'id', 'id', 'plan_id');
    }

    public function settings(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(UserWebsiteSetting::class, 'user_website_id', 'id');
    }

    public function pages(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Page::class, 'user_website_id', 'id');
    }

    public function visitorDefinedDialogues(): HasManyThrough
    {
        return $this->hasManyThrough(VisitorDefinedDialogue::class, Page::class, 'user_website_id', 'page_id', 'id', 'id');
    }

    public function visitorDefinedDialogueSessions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(VisitorDefinedDialogueSession::class, 'user_website_id', 'id');
    }

    public function isQueryWithLatestActive(): bool
    {
        return true;
    }
}
