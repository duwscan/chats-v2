<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int|null $parent_id
 * @property int|null $user_website_id
 * @property int|null $channel_webhook_config_id
 * @property string|null $username
 * @property string|null $display_name
 * @property string|null $avatar_url
 * @property string|null $email
 * @property string|null $phone
 * @property string|null $channel
 * @property string|null $channel_user_id
 * @property array|null $settings
 * @property string|null $timezone
 * @property string|null $locale
 * @property \Illuminate\Support\Carbon|null $last_activity_at
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 */
class CustomerModel extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'customers';

    protected $fillable = [
        'username',
        'display_name',
        'avatar_url',
        'email',
        'phone',
        'channel',
        'channel_user_id',
        'settings',
        'timezone',
        'locale',
        'last_activity_at',
        'parent_id',
        'user_website_id',
        'channel_webhook_config_id',
    ];

    protected function casts(): array
    {
        return [
            'settings' => 'array',
            'last_activity_at' => 'datetime',
        ];
    }

    protected static function newFactory()
    {
        return \Database\Factories\CustomerFactory::new();
    }

    public function conversations(): HasMany
    {
        return $this->hasMany(ConversationModel::class, 'customer_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(MessageModel::class, 'customer_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(CustomerModel::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(CustomerModel::class, 'parent_id');
    }

    public function statuses(): HasMany
    {
        return $this->hasMany(CustomerStatusModel::class, 'customer_id');
    }

    public function currentStatus(): HasMany
    {
        return $this->hasMany(CustomerStatusModel::class, 'customer_id')
            ->latest('status_changed_at')
            ->limit(1);
    }

    public function latestStatus(): ?CustomerStatusModel
    {
        $status = $this->statuses()->latest('status_changed_at')->first();

        return $status instanceof CustomerStatusModel ? $status : null;
    }

    // Notification relationships
    public function notifications(): MorphMany
    {
        return $this->morphMany(NotificationModel::class, 'notifiable');
    }

    public function notificationPreferences(): MorphMany
    {
        return $this->morphMany(NotificationPreferenceModel::class, 'notifiable');
    }

    // Helper methods for notifications
    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function unreadNotifications()
    {
        return $this->notifications()->unread();
    }

    public function getUnreadNotificationCount()
    {
        return $this->unreadNotifications()->count();
    }

    /**
     * Get the user website that owns this customer
     */
    public function userWebsite(): BelongsTo
    {
        return $this->belongsTo(UserWebsite::class, 'user_website_id');
    }

    /**
     * Get the channel webhook config associated with the customer.
     */
    public function channelWebhookConfig(): BelongsTo
    {
        return $this->belongsTo(ChannelWebhookConfig::class, 'channel_webhook_config_id');
    }
}
