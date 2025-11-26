<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApiKey extends Model
{
    use SoftDeletes;

    protected $table = 'api_keys';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'key',
        'description',
        'engine',
        'account_name',
        'account_type',
        'account_password',
        'quota_day',
        'quota_minute',
        'quota_second',
    ];
}
