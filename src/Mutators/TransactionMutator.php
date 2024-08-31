<?php

namespace Wovosoft\LaravelCashier\Mutators;

use Illuminate\Database\Eloquent\Model;
use Wovosoft\LaravelCashier\Enums\TransactionType;
use Wovosoft\LaravelCashier\Models\Payment;
use Wovosoft\LaravelCashier\Models\Transaction;

class TransactionMutator
{
    private ?Model $accountable = null;

    public static function init(): static
    {
        return new static();
    }

    public function setAccountable(Model $accountable): static
    {
        $this->accountable = $accountable;
        return $this;
    }

    /**
     * @throws \Throwable
     */
    public function make(
        TransactionType $type,
        int|float $amount,
        ?string $reference = null
    ): Transaction {
        $transaction = new Transaction();
        $transaction->forceFill([
            'accountable_id'   => $this->accountable?->id,
            'accountable_type' => !is_null($this->accountable) ? get_class($this->accountable) : null,
            'reference'        => $reference,
            'type'             => $type,
            'created_by_id'    => auth()->id(),
            'amount'           => $amount
        ]);

        $transaction->saveOrFail();

        return $transaction;
    }
}
