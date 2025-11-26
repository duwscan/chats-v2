<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Transaction extends Model
{
    protected $table = 'transactions';

    protected $fillable = [
        'transactionable_type',
        'transactionable_id',
        'amount',
        'type',
        'status',
        'payment_id',
    ];

    protected $hidden = ['transactionable_type', 'transactionable_id'];

    public function transactionable(): MorphTo
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->hasOneThrough(User::class, Payment::class, 'id', 'id', 'payment_id', 'user_id');
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class, 'payment_id');
    }
}
