<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Prompt extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'page_id', // nullable, null for all site scope
        'name',
        'content',
        'type', // question or answer or statement ...
        'engine',
        'language_id',
    ];
}
