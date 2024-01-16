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

        $request->validate([
            'invoice_date'      => ['required','date','after_or_equal:today'],
            'due_date'          => ['required','date','after_or_equal:today'],
            'items'             => ['required','array'],
            'note'              => ['nullable'],
            'amount_received'   => ['required'],
            'payment_method'    => ['required','in:pos,transfer,cash'],
            'discount'          => ['nullable'],
            'tax'               => ['nullable'],
            'shipping_fee'      => ['nullable']
        ]);

        $this->repository->create($request->all());

        return jsonResponse(Response::HTTP_OK, $this->customer->load('profile'));
    }
}
