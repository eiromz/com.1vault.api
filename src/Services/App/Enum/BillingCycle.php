<?php

namespace Src\Services\App\Enum;

enum BillingCycle: string
{
    case YEARLY = 'yearly';
    case MONTHLY = 'monthly';
    case QUARTERLY = 'quarterly';

    case ONETIME = 'one-time';
}
