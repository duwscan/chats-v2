<?php

namespace App\Utilities;

interface Transactionable
{
    public function getTransactionAmount(): ?int;

    public function getTransactionId(): ?int;

    public function getTransactionType(): string;

    public function getTransactionCode(): ?string;
}
