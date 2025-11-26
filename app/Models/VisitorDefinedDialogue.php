<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VisitorDefinedDialogue extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'visitor_defined_dialogue_session_id', // nullable
        'thread_id', // unique string
        'parent_id',
        'input',
        'output',
        'page_id',
        'reported_at', // nullable
        'report_type_id', // nullable
        'report_note', // nullable
    ];

    protected $casts = [
        'reported_at' => 'datetime',
    ];

    public function parent()
    {
        return $this->belongsTo(VisitorDefinedDialogue::class, 'parent_id');
    }

    public function visitorDefinedDialogueSession()
    {
        return $this->belongsTo(VisitorDefinedDialogueSession::class, 'visitor_defined_dialogue_session_id');
    }

    public function page()
    {
        return $this->belongsTo(Page::class);
    }
}
