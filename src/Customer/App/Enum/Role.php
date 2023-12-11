<?php

namespace Src\Customer\App\Enum;

enum Role: string
{
    case BUSINESS_OWNER = 'customer';
    case EMPLOYEE = 'employee';
}
