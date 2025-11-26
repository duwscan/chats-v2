<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class FcmTokenModel extends Model
{
    use SoftDeletes;

    protected $table = 'fcm_tokens';

    protected $fillable = [
        'tokenable_type',
        'tokenable_id',
        'token',
        'device_id',
        'app_version',
        'os',
        'device_model',
    ];

    protected function casts(): array
    {
        return [
            'token' => 'string',
            'device_id' => 'string',
            'app_version' => 'string',
            'os' => 'string',
            'device_model' => 'string',
        ];
    }

    public function tokenable(): MorphTo
    {
        return $this->morphTo();
    }

    public static function addToken(
        Model $model,
        string $token,
        string $deviceId,
        ?string $appVersion = null,
        ?string $os = null,
        ?string $deviceModel = null
    ): self {
        return DB::transaction(function () use ($model, $token, $deviceId, $appVersion, $os, $deviceModel) {
            static::where('device_id', $deviceId)
                ->lockForUpdate()
                ->delete();

            return static::create([
                'tokenable_type' => get_class($model),
                'tokenable_id' => $model->id,
                'token' => $token,
                'device_id' => $deviceId,
                'app_version' => $appVersion,
                'os' => $os,
                'device_model' => $deviceModel,
            ]);
        });
    }

    public static function removeToken(string $token): bool
    {
        return static::where('token', $token)->delete() > 0;
    }

    public static function removeTokenByDeviceId(string $deviceId): bool
    {
        return static::where('device_id', $deviceId)->delete() > 0;
    }

    public static function clearTokensFor(Model $model): int
    {
        return static::where('tokenable_type', get_class($model))
            ->where('tokenable_id', $model->id)
            ->delete();
    }
}
