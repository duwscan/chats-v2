<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Traits\HasFcmTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasFcmTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Notification relationships
    public function notifications()
    {
        return $this->morphMany(NotificationModel::class, 'notifiable');
    }

    public function notificationPreferences()
    {
        return $this->morphMany(NotificationPreferenceModel::class, 'notifiable');
    }

    // Helper methods for notifications
    public function unreadNotifications()
    {
        return $this->notifications()->unread();
    }

    public function getUnreadNotificationCount()
    {
        return $this->unreadNotifications()->count();
    }

    /**
     * Specifies the user's FCM tokens
     */
    public function routeNotificationForFcm(): array
    {
        return $this->getFcmTokens();
    }

    /**
     * Get the websites owned by this user
     */
    public function websites(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(UserWebsite::class, 'user_id');
    }
}
