<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserDefinedDialogueRule extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'page_id',
        'user_website_id',
        'input',
        'output',
        'type',
        'index'
    ];
}
