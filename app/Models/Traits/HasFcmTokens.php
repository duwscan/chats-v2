<?php

namespace App\Models\Traits;

use App\Models\FcmTokenModel;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasFcmTokens
{
    public function fcmTokens(): MorphMany
    {
        return $this->morphMany(FcmTokenModel::class, 'tokenable');
    }

    public function addFcmToken(
        string $token, 
        string $deviceId, 
        ?string $appVersion = null, 
        ?string $os = null, 
        ?string $deviceModel = null
    ): FcmTokenModel {
        return FcmTokenModel::addToken($this, $token, $deviceId, $appVersion, $os, $deviceModel);
    }

    public function removeFcmToken(string $token): bool
    {
        return FcmTokenModel::removeToken($token);
    }

    public function removeFcmTokenByDeviceId(string $deviceId): bool
    {
        return FcmTokenModel::removeTokenByDeviceId($deviceId);
    }

    public function clearFcmTokens(): int
    {
        return FcmTokenModel::clearTokensFor($this);
    }

    public function getFcmTokens(): array
    {
        return $this->fcmTokens()->pluck('token')->toArray();
    }
}
