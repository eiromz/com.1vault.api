<?php

namespace Src\Services\App\Http;

use App\Http\Controllers\DomainBaseCtrl;
use App\Models\Journal;
use App\Models\Service;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Src\Services\App\Http\Requests\ServiceRequest;
use Symfony\Component\HttpFoundation\Response;

class BusinessAnalyticsCtrl extends DomainBaseCtrl
{
    public function index(Request $request)
    {
        //select * from journal and group them by label

        Journal::query()->where('debit','=',true);

    }
}
