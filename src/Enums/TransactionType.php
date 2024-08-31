<?php

namespace Wovosoft\LaravelCashier\Enums;

enum TransactionType: string
{
    case Credit = 'credit';

    case Debit = 'debit';

    public function isCredit(): bool
    {
        return $this->value == self::Credit;
    }

    public function isDebit(): bool
    {
        return $this->value == self::Debit;
    }
}
