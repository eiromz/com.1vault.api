<?php

namespace Src\Customer\App\Enum;

enum AccountStatus:int
{
    case PENDING = 0;
    case ACTIVE = 1;
    case SUSPENDED = 2;
    case BLOCKED = 3;
    case CONTACT_ADMIN = 4;
    case FRAUD = 5;
}
