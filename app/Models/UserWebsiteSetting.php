<?php

namespace App\Models;

use App\Builder\CastValueAttribute;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserWebsiteSetting extends Model
{
    use SoftDeletes, CastValueAttribute;

    protected $fillable = [
        'user_website_id',
        'key',
        'value',
        'type',
    ];

    public function userWebsite()
    {
        return $this->belongsTo(UserWebsite::class);
    }
}
