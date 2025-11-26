<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VisitorDefinedDialogueSession extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', // autogen uuid
        'ip_address', // default null
        'user_agent', // default null
        'started_at', // default now
        'user_website_id',
        'email',
        'phone_number',
        'bot_id',
    ];
    protected $casts = [
        'started_at' => 'datetime',
    ];

    public function visitorDefinedDialogues()
    {
        return $this->hasMany(VisitorDefinedDialogue::class);
    }

    public function website()
    {
        return $this->belongsTo(UserWebsite::class, 'user_website_id');
    }
}
