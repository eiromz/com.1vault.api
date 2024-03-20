<?php

namespace Src\Services\App\Http;

use App\Http\Controllers\DomainBaseCtrl;
use App\Models\Invoice;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class BusinessAnalyticsCtrl extends DomainBaseCtrl
{
    public function index(): JsonResponse
    {
        $expenses = $this->expenses();
        $invoices = $this->invoices();

        $collection = collect([
            'expenses' => $expenses->all(),
            'invoices' => [
                'total' => $invoices?->sum('total') ?? 0,
                'paid' => $invoices?->sum('amount_received') ?? 0,
            ],
        ]);

        return jsonResponse(Response::HTTP_OK, $collection);
    }

    private function expenses()
    {

        return DB::table('journals')
            ->select('label', DB::raw('SUM(amount) AS total'))
            ->groupBy('label')
            ->where('customer_id', '=', auth()->user()->id)
            ->where('debit', '=', true)
            ->get();
    }

    private function invoices()
    {
        return Invoice::query()
            ->select(['amount_received', 'total'])
            ->without(['customer', 'service'])
            ->where('customer_id', '=', auth()->user()->id)
            ->get();
    }
}
