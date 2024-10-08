<?php

namespace Src\Wallets\Payments\App\Http;

use App\Http\Controllers\DomainBaseCtrl;
use App\Models\Beneficiary;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class BeneficiaryCtrl extends DomainBaseCtrl
{
    public function index(): JsonResponse
    {
        $beneficiaries = Beneficiary::query()
            ->select(['bank_account_name', 'bank_account_number', 'bank_code', 'bank_name', 'created_at'])
            ->where('customer_id', '=', auth()->user()->id)
            ->limit(100)
            ->get();

        return jsonResponse(Response::HTTP_OK, $beneficiaries);
    }

    public function view(Beneficiary $beneficiary): JsonResponse
    {
        return jsonResponse(Response::HTTP_OK, $beneficiary);
    }
}
