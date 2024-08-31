<?php

namespace Wovosoft\LaravelCashier\Mutators;

use Wovosoft\LaravelCashier\Enums\TransactionType;
use Wovosoft\LaravelCashier\Models\Income;
use Wovosoft\LaravelCashier\Models\Payment;

class IncomeMutator
{
    public Payment $payment;

    public string $type;

    public ?int $ownerId = null;

    public static function init(): static
    {
        return new static;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function setPayment(Payment $payment): static
    {
        $this->payment = $payment;

        return $this;
    }

    public function setOwnerId(?int $ownerId = null): static
    {
        $this->ownerId = $ownerId;

        return $this;
    }

    /**
     * @throws \Throwable
     */
    public function process(): Income
    {
        $income = new Income;
        $income->forceFill([
            'payment_id' => $this->payment->id,
            'owner_id' => $this->ownerId,
            'type' => $this->type,
            'amount' => $this->type == 'admin' ? $this->payment->admin_fee : $this->payment->agent_fee,
        ]);
        $income->saveOrFail();

        TransactionMutator::init()
            ->setAccountable($income)
            ->make(
                type     : TransactionType::Credit,
                amount   : $income->amount,
                reference: 'Income : '.$income->id
            );

        return $income;
    }
}
