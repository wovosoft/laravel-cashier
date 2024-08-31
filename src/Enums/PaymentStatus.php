<?php

namespace Wovosoft\LaravelCashier\Enums;

enum PaymentStatus: string
{
    case Pending   = 'pending';
    case Completed = 'completed';
    case Failed    = 'failed';
}
