<?php

namespace Src\Accounting\App\Http;

use App\Http\Controllers\DomainBaseCtrl;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Src\Accounting\App\Requests\CreateInvoiceRequest;
use Src\Accounting\App\Requests\UpdateInvoiceRequest;
use Src\Accounting\Domain\Repository\Interfaces\InvoiceRepositoryInterface;
use Symfony\Component\HttpFoundation\Response;

class InvoiceCtrl extends DomainBaseCtrl
{
    private $repository;

    public array $storeRequestFilterKeys = [
        'client_id', 'business_id', 'invoice_date', 'due_date', 'items', 'note',
        'amount_received', 'payment_method', 'discount', 'tax', 'shipping_fee', 'total',
        'payment_status',
    ];

    public array $updateRequestFilterKeys = [
        'invoice_date', 'due_date', 'items', 'note',
        'amount_received', 'payment_method', 'discount', 'tax', 'shipping_fee', 'total',
        'payment_status',
    ];

    public function __construct(InvoiceRepositoryInterface $repository)
    {
        $this->repository = $repository;
        parent::__construct();
    }

    /**
     * @throws Exception
     */
    public function store(CreateInvoiceRequest $request): JsonResponse
    {
        $this->repository->setUser(auth()->user());

        $request->merge([
            'client_id' => $request->client,
            'business_id' => $request->business,
            'payment_status' => 0,
        ]);

        $request->validated();

        if ($request->amount_received >= $request->total) {
            $request->merge(['payment_status' => 1]);
        }

        $data = $this->repository->create($request->only($this->storeRequestFilterKeys));

        return jsonResponse(Response::HTTP_OK, $data);
    }

    public function destroy(Request $request): JsonResponse
    {
        $this->repository->setUser(auth()->user());
        $request->validate([
            'invoice' => ['required', 'exists:App\Models\Invoice,id'],
        ]);

        if (! $this->repository->delete($request->invoice)) {
            return jsonResponse(Response::HTTP_BAD_REQUEST, [
                'message' => 'Failed to Delete Invoice',
            ]);
        }

        return jsonResponse(Response::HTTP_OK, [
            'message' => 'Invoice Deleted',
        ]);
    }

    public function update($id, UpdateInvoiceRequest $request): JsonResponse
    {
        $this->repository->setUser(auth()->user());
        $request->validated();

        if (! $this->repository->update($id, $request->only($this->updateRequestFilterKeys))) {
            return jsonResponse(Response::HTTP_BAD_REQUEST, [
                'message' => 'Failed to update invoice',
            ]);
        }

        return jsonResponse(Response::HTTP_OK, [
            'message' => 'Invoice Updated',
        ]);
    }

    public function view($invoice, $business, Request $request): JsonResponse
    {
        $this->repository->setUser(auth()->user());
        $request->merge([
            'invoice' => $invoice,
            'business' => $business,
        ]);

        $request->validate([
            'business' => ['required', 'exists:App\Models\Business,id'],
            'invoice' => ['required', 'exists:App\Models\Invoice,id'],
        ]);

        $data = $this->repository->getDetailsByParams([
            'id' => $invoice,
            'business_id' => $business,
        ]);

        return jsonResponse(Response::HTTP_OK, $data);
    }

    public function index($business, Request $request)
    {
        $this->repository->setUser(auth()->user());
        $request->merge(['business' => $business]);

        $request->validate([
            'business' => ['required', 'exists:App\Models\Business,id'],
        ]);

        $data = $this->repository->getAllByParams([
            'business_id' => $business,
        ]);

        return jsonResponse(Response::HTTP_OK, $data);
    }
}
