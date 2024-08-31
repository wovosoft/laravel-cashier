# Payment Mutator

## Make Payment Log

```php
<?php
use Wovosoft\LaravelCashier\Models\Payment;
use Wovosoft\LaravelCashier\Mutators\PaymentsMutator;

$slip=\App\Models\Slip::first();


$payment= PaymentsMutator::init()
    ->setAdminFee(300)
    ->setAgentFee(200)
    ->setPaymentAmount(1000)
    ->setSlip($slip)
    ->setPaymentStatus($this->payment->status)
    ->create();
```

## Make Payment Successful

Use PaymentMutator Mutator class. This automatically processes payment as successful.
Creates Income and Transaction records.

```php
<?php
use Wovosoft\LaravelCashier\Models\Payment;
use Wovosoft\LaravelCashier\Mutators\PaymentsMutator;

//payment to be processed
$payment=\Wovosoft\LaravelCashier\Models\Payment::first();

//mutator, processes payment as successful, and performs other actions
//i.e. adjust balance, creates transaction logs
$mutator = PaymentMutator::init()
    ->setPayment($payment)  //set payment model
    ->completePayment(); //complete payment
```

## Make Payment Failed

Use PaymentMutator Mutator class. This automatically processes payment as failed.
Returns payment funds to the customer's account.

```php
<?php
use Wovosoft\LaravelCashier\Models\Payment;
use Wovosoft\LaravelCashier\Mutators\PaymentsMutator;

//payment to be processed
$payment=Payment::first();

//mutator, processes payment as successful, and performs other actions
//i.e. adjust balance, creates transaction logs
$mutator = PaymentMutator::init()
    ->setPayment($payment)  //set payment model
    ->failPayment(); //complete payment
```

### Get Date Ranged Income

```php
<?php
use Wovosoft\LaravelCashier\Models\Income;

$data= Income::query()
    ->whereBetween('created_at', ['2022-01-01', '2022-01-31'])
    ->get();
```

### Admin Income

```php
<?php
use Wovosoft\LaravelCashier\Models\Income;

$data= Income::query()
    ->adminIncome()
    ->whereBetween('created_at', ['2022-01-01', '2022-01-31'])
    ->get();
```

### Agent Income

```php
<?php
use Wovosoft\LaravelCashier\Models\Income;

$data= Income::query()
    ->agentIncome()
    ->whereBetween('created_at', ['2022-01-01', '2022-01-31'])
    ->get();
```

You can perform other aggregate queries as well like, sum, count, etc.
