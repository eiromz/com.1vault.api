<?php

namespace Src\Wallets\Payments\App\Enum;

enum ErrorMessages: string
{
    case FETCH_BANK_LIST_FAILED = 'Operation failed at this time try again';
    case FETCH_ACCOUNT_DETAILS_FAILED = 'Failed to fetch account information';
}
