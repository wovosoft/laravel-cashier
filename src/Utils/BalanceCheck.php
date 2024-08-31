<?php

namespace Wovosoft\LaravelCashier\Utils;

use App\Models\User;

class BalanceCheck
{
    public static function totalDeposit(): int|float
    {
        return User::query()->sum('balance');
    }

    public static function balanceOfUser(int|User $user): int|float
    {
        return ($user instanceof User ? $user->balance : User::find($user)?->balance) ?? 0;
    }

    public static function totalIncome()
    {

    }

    public static function availableIncome()
    {

    }
}
