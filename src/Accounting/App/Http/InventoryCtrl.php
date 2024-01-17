<?php

namespace Src\Accounting\App\Http;

use App\Http\Controllers\DomainBaseCtrl;
use Illuminate\Http\JsonResponse;
use Src\Accounting\App\Requests\CreateInventoryRequest;
use Src\Accounting\Domain\Repository\Interfaces\InventoryRepositoryInterface;
use Symfony\Component\HttpFoundation\Response;

class InventoryCtrl extends DomainBaseCtrl
{
    private $repository;

    public array $requestKeysFilterCreate = [
        'amount','is_published','product_name','unit','quantity','business_id','selling_price'
    ];

    public function __construct(InventoryRepositoryInterface $repository)
    {
        $this->repository = $repository;
        parent::__construct();
    }

    public function store(CreateInventoryRequest $request): JsonResponse
    {
        $this->repository->setUser(auth()->user());

        $request->merge([
            'is_published' => 1,
            'product_name' => $request->name,
            'business_id' => $request->business
        ]);
        $request->validated();

        $data = $this->repository->create($request->only($this->requestKeysFilterCreate));

        return jsonResponse(Response::HTTP_OK, $data);
    }
}
