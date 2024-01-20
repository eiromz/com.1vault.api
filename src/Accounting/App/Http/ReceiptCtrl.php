<?php

namespace Src\Accounting\App\Http;

use App\Http\Controllers\DomainBaseCtrl;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Src\Accounting\App\Requests\CreateReceiptRequest;
use Src\Accounting\Domain\Repository\Interfaces\ReceiptRepositoryInterface;
use Symfony\Component\HttpFoundation\Response;

class ReceiptCtrl extends DomainBaseCtrl
{
    private $repository;

    public array $storeRequestFilterKeys = [
        'client_id', 'business_id', 'transaction_date', 'items', 'description',
        'amount_received', 'payment_method', 'discount', 'tax',  'total'
    ];

    public array $updateRequestFilterKeys = [
        'transaction_date', 'items', 'description',
        'amount_received', 'payment_method', 'discount', 'tax', 'total'
    ];

    public function __construct(ReceiptRepositoryInterface $repository)
    {
        $this->repository = $repository;
        parent::__construct();
    }

    /**
     * @throws Exception
     */
    public function store(CreateReceiptRequest $request): JsonResponse
    {
        $this->repository->setUser(auth()->user());

        $request->merge([
            'client_id' => $request->client,
            'business_id' => $request->business,
        ]);

        $request->validated();

        $data = $this->repository->create($request->only($this->storeRequestFilterKeys));

        return jsonResponse(Response::HTTP_OK, $data);
    }

    public function destroy(Request $request): JsonResponse
    {
        $this->repository->setUser(auth()->user());

        $request->validate([
            'receipt' => ['required', 'exists:App\Models\Receipt,id'],
        ]);

        if (!$this->repository->delete($request->receipt)) {
            return jsonResponse(Response::HTTP_BAD_REQUEST, [
                'message' => 'Failed to Delete Receipt',
            ]);
        }

        return jsonResponse(Response::HTTP_OK, [
            'message' => 'Receipt Deleted',
        ]);
    }

    public function update($id, Request $request): JsonResponse
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
