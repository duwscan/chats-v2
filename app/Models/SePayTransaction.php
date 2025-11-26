<?php

namespace App\Models;

use App\Utilities\Transactionable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
// use SePay\SePay\Models\SePayTransaction as SePayTransactionPackageModel;

class SePayTransaction extends Model implements Transactionable
{
    protected $fillable = [
        'gateway',
        'transactionDate',
        'accountNumber',
        'subAccount',
        'code',
        'content',
        'transferType',
        'description',
        'transferAmount',
        'referenceCode',
        'webhook_id'
    ];

    protected $table = "sepay_transactions";
    public function transaction(): MorphOne
    {
        return $this->morphOne(Transaction::class, 'transactionable');
    }

    public function getTransactionAmount(): ?int
    {
        return $this->transferAmount;
    }

    public function getTransactionId(): ?int
    {
        return $this->id;
    }

    public function getTransactionType(): string
    {
        return $this->getMorphClass();
}

    public function getTransactionCode(): ?string
    {
        return $this->referenceCode;
    }
}
