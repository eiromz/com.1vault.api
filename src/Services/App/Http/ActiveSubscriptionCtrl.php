<?php

namespace Src\Services\App\Http;

use App\Http\Controllers\DomainBaseCtrl;
use App\Models\Subscription;
use Src\Services\App\Http\Resource\SubscriptionResource;
use Symfony\Component\HttpFoundation\Response;

class ActiveSubscriptionCtrl extends DomainBaseCtrl
{
    public function index()
    {
        return 'true';
    }
}
