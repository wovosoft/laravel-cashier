<?php

namespace Wovosoft\LaravelCashier\Mutators;

use App\Models\Slip;
use Illuminate\Support\Fluent;
use Wovosoft\LaravelCashier\Enums\PaymentStatus;
use Wovosoft\LaravelCashier\Enums\TransactionType;
use Wovosoft\LaravelCashier\Models\Income;
use Wovosoft\LaravelCashier\Models\Payment;
use Wovosoft\LaravelCashier\Models\Transaction;

class PaymentsMutator
{
    private Payment       $payment;
    private int|float     $adminFee      = 0;
    private int|float     $agentFee      = 0;
    private int|float     $paymentAmount = 0;
    private Slip          $slip;
    private PaymentStatus $paymentStatus;

    public static function init(): static
    {
        return new static();
    }


    public function setAdminFee(int|float $fee): static
    {
        $this->adminFee = $fee;
        return $this;
    }

    public function setAgentFee(int|float $fee): static
    {
        $this->agentFee = $fee;
        return $this;
    }

    public function setPaymentAmount(int|float $amount): static
    {
        $this->paymentAmount = $amount;
        return $this;
    }

    public function setSlip(Slip $slip): static
    {
        $this->slip = $slip;
        return $this;
    }

    public function setPaymentStatus(PaymentStatus $status): static
    {
        $this->paymentStatus = $status;
        return $this;
    }

    public function fromPayment(): static
    {
        return $this
            ->setAdminFee($this->payment->admin_fee)
            ->setAgentFee($this->payment->agent_fee)
            ->setPaymentAmount($this->payment->payment_amount)
            ->setSlip($this->payment->slip)
            ->setPaymentStatus($this->payment->status);
    }

    public function setPayment(Payment $payment): static
    {
        $this->payment = $payment;
        return $this;
    }

    /**
     * @throws \Throwable
     */
    public function failPayment(): Payment
    {
        $this->payment
            ->forceFill([
                'completed_at' => null,
                //if returning funds to the customer is happened by another process then adjust it from that process
                //otherwise, set it to current timestamp
                'returned_at'  => now(),
                'failed_at'    => now(),
                'status'       => PaymentStatus::Failed
            ])
            ->saveOrFail();

        //Adjust Customer's Balance
        $this->slip->user()->increment('balance', $this->payment->payment_amount);

        //there should have two transactions, one for user and one for admin (from account, to account)
        TransactionMutator::init()
            ->setAccountable($this->payment)
            ->make(
                type     : TransactionType::Debit,
                amount   : $this->payment->payment_amount,
                reference: "Refunded : ".$this->payment->id
            );

        return $this->payment;
    }

    /**
     * Access Returned Data: {payment, adminIncome, agentIncome} or [payment, adminIncome, agentIncome]
     * @throws \Throwable
     */
    public function completePayment(): Fluent
    {
        $this->payment
            ->forceFill([
                'completed_at' => now(),
                'returned_at'  => null,
                'failed_at'    => null,
                'status'       => PaymentStatus::Completed
            ])
            ->saveOrFail();

        //adjust admin balance
        $adminIncome = IncomeMutator::init()
            ->setPayment($this->payment)
            ->setType('admin')
            ->setOwnerId()
            ->process();

        $agentIncome = null;
        //adjust user balance
        if ($this->agentFee > 0) {
            $agentIncome = IncomeMutator::init()
                ->setPayment($this->payment)
                ->setType('agent')
                ->setOwnerId()
                ->process();
        }

        return fluent([
            'payment'     => $this->payment,
            'adminIncome' => $adminIncome,
            'agentIncome' => $agentIncome
        ]);
    }
}
