<?php

namespace Src\Accounting\App\Enum;

enum Messages: string
{
    case NOT_FOUND = 'We could not find what you were looking for';
    case UPDATE_FAILED = 'Update Failed';
    case DELETED = 'Deleted ';
    case UPDATED = 'Updated';
    case UNAUTHORIZED = 'You are not authorized to carry out this action';
}
