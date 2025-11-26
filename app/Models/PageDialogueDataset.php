<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PageDialogueDataset extends Model
{
    use SoftDeletes;

    protected $table = 'page_dialogue_datasets';

    protected $fillable = [
        'page_id',
        'input',
        'output',
        'priority',
        'activated_at',
        'edited_by'
    ];

    protected $casts = [
        'activated_at' => 'datetime',
    ];

}
