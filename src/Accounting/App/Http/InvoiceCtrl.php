<?php

namespace Src\Accounting\App\Http;

use App\Http\Controllers\DomainBaseCtrl;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Src\Accounting\Domain\Repository\Interfaces\InvoiceRepositoryInterface;
use Symfony\Component\HttpFoundation\Response;

class InvoiceCtrl extends DomainBaseCtrl
{
    private $repository;

    public array $requestKeysFilter = [
        'client_id', 'business_id', 'invoice_date', 'due_date', 'items', 'note',
        'amount_received', 'payment_method', 'discount', 'tax', 'shipping_fee',
    ];

    public function __construct(InvoiceRepositoryInterface $repository)
    {
        $this->repository = $repository;
        parent::__construct();
    }

    /**
     * @throws Exception
     */
    public function store(Request $request): JsonResponse
    {
        $this->repository->setUser(auth()->user());

        $request->merge([
            'client_id' => $request->client,
            'business_id' => $request->business,
        ]);

        $request->validate([
            'invoice_date' => ['required', 'date', 'after_or_equal:today'],
            'due_date' => ['required', 'date', 'after_or_equal:today'],
            'items' => ['required', 'array'],
            'note' => ['nullable'],
            'amount_received' => ['required'],
            'payment_method' => ['required', 'in:pos,transfer,cash'],
            'discount' => ['nullable'],
            'tax' => ['required'],
            'shipping_fee' => ['required'],
            'client' => ['nullable', 'exists:App\Models\Client,id'],
            'business' => ['nullable', 'exists:App\Models\Business,id'],
        ]);

        //dd($request->only($this->requestKeysFilter));

        //$data = $this->repository->create($request->all());

        return jsonResponse(Response::HTTP_OK, $request->only($this->requestKeysFilter));
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
}
