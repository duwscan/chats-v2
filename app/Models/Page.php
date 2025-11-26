<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Page extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_website_id',
        'url',
        'title',
        'content',
        'crawled_at',
        'analyzed_at',
    ];

    protected $casts = [
        'crawled_at' => 'datetime',
        'analyzed_at' => 'datetime',
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\HasOneThrough
    {
        return $this->hasOneThrough(
            User::class,
            UserWebsite::class,
            'id',
            'id',
            'user_website_id',
            'user_id'
        );
    }

    public function userWebsite(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(UserWebsite::class, 'user_website_id', 'id');
    }

    public function pageDialogueDatasets(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PageDialogueDataset::class, 'page_id', 'id');
    }

    public function orderedLimitedPageDialogueDatasets(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PageDialogueDataset::class, 'page_id', 'id')
            ->orderBy('priority', 'asc')
            ->orderByRaw('RAND()')
            ->limit(2);
    }

    public function visitorDefinedDialogues()
    {
        return $this->hasMany(VisitorDefinedDialogue::class);
    }

    public function pageDialogueDataset() : HasMany
    {
        return $this->hasMany(PageDialogueDataset::class);
    }
}
