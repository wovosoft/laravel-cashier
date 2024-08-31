<?php

namespace Wovosoft\LaravelCashier\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Wovosoft\LaravelCashier\LaravelCashier
 */
class LaravelCashier extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Wovosoft\LaravelCashier\LaravelCashier::class;
    }
}
