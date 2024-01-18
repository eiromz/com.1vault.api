<?php

namespace Src\Accounting\App\Http;

use App\Http\Controllers\DomainBaseCtrl;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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

    public function destroy(Request $request): JsonResponse
    {
        $this->repository->setUser(auth()->user());

        $request->validate([
            'inventory' => ['required', 'exists:App\Models\Inventory,id'],
        ]);

        if (!$this->repository->delete($request->inventory)) {
            return jsonResponse(Response::HTTP_BAD_REQUEST, [
                'message' => 'Failed to Delete Inventory',
            ]);
        }

        return jsonResponse(Response::HTTP_OK, [
            'message' => 'Inventory Deleted',
        ]);
    }

    public function index($id,Request $request)
    {
        $this->repository->setUser(auth()->user());

        $request->merge(['business' => $id]);

        $request->validate([
            'business' => ['required','exists:App\Models\Business,id']
        ]);

        $collection = collect([
            'inventory_count' => $this->repository->getCountAll(['business_id' => $id]),
            'inventory_total_value' => $this->repository->getSum(['business_id' => $id])
        ]);

        $data = $this->repository->getAllByParams([
            'business_id' => $id
        ]);

        $collection->put('inventory_list',$data->all());

        return jsonResponse(Response::HTTP_OK, $collection);
    }

    /**
     * @param $inventory
     * @param $business
     * @param Request $request
     * @return JsonResponse
     */
    public function view($inventory, $business, Request $request)
    {
        $this->repository->setUser(auth()->user());

        $request->merge([
            'inventory' => $inventory,
            'business' => $business
        ]);

        $request->validate([
            'business' => ['required','exists:App\Models\Business,id'],
            'inventory' => ['required','exists:App\Models\Inventory,id']
        ]);

        $data = $this->repository->getDetailsByParams([
            'id' => $inventory,
            'business_id' => $business,
        ]);

        return jsonResponse(Response::HTTP_OK, $data);
    }
}
