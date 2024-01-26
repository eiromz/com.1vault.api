<?php

namespace Src\Accounting\App\Http;

use App\Http\Controllers\DomainBaseCtrl;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Src\Accounting\Domain\Repository\Interfaces\InvoiceRepositoryInterface;
use Symfony\Component\HttpFoundation\Response;

class ReportCtrl extends DomainBaseCtrl
{
    private $repository;

    public array $indexRequestFilterKeys = [
        'business_id', 'payment_status',
    ];

    public function __construct(InvoiceRepositoryInterface $repository)
    {
        $this->repository = $repository;
        parent::__construct();
    }

    /**
     * @throws Exception
     */
    public function index(Request $request): JsonResponse
    {
        $request->validate([
            'type' => ['required', 'in:sales,debtors'],
            'start_date' => ['required', 'date_format:Y-m-d'],
            'end_date' => ['required', 'date_format:Y-m-d'],
            'business' => ['required', 'exists:App\Models\Business,id'],
        ]);

        $payment_status = ($request->type === 'debtors') ? 0 : 1;

        $request->merge([
            'business_id' => $request->business,
            'payment_status' => $payment_status,
        ]);

        $this->repository->setUser(auth()->user());

        $data = $this->repository->getSalesAndDebtorList(
            $request->only($this->indexRequestFilterKeys),
            $request->start_date, $request->end_date
        );

        return jsonResponse(Response::HTTP_OK, $data);
    }
}
