<?php

namespace Src\Accounting\App\Http;

use App\Http\Controllers\DomainBaseCtrl;
use App\Models\StoreFront;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Src\Accounting\App\Requests\CreateStoreFrontInventoryRequest;
use Src\Accounting\Domain\Repository\Interfaces\InventoryRepositoryInterface;
use App\Exceptions\BaseException;
use Symfony\Component\HttpFoundation\Response;

class StoreFrontInventoryCtrl extends DomainBaseCtrl
{
    private $repository;

    public array $requestKeysFilterCreate = [
        'amount', 'is_published', 'product_name', 'unit', 'quantity', 'business_id',
        'selling_price', 'is_store_front', 'image', 'stock_status', 'description',
    ];

    public array $requestKeysFilterUpdate = [
        'amount', 'product_name', 'description', 'image', 'stock_status', 'is_published',
    ];

    public function __construct(InventoryRepositoryInterface $repository)
    {
        $this->repository = $repository;
        parent::__construct();
    }

    /**
     * @throws BaseException
     */
    public function store(CreateStoreFrontInventoryRequest $request): JsonResponse
    {
        $this->repository->setUser(auth()->user());

        $current_inventory_size = $this->repository->totalInventory([]);

        $inventory_limit = getInventorySizeLimit(StoreFront::query(), auth()->user());

        if ($current_inventory_size >= $inventory_limit) {
            throw new BaseException('You have reached the inventory limit', Response::HTTP_BAD_REQUEST);
        }

        $request->execute();

        $data = $this->repository->create($request->only($this->requestKeysFilterCreate));

        return jsonResponse(Response::HTTP_OK, $data);
    }

    public function destroy(Request $request): JsonResponse
    {
        $this->repository->setUser(auth()->user());

        $request->validate([
            'inventory' => ['required'],
        ]);

        if (! $this->repository->delete($request->inventory)) {
            return jsonResponse(Response::HTTP_BAD_REQUEST, [
                'message' => 'Failed to Delete Inventory',
            ]);
        }

        return jsonResponse(Response::HTTP_OK, [
            'message' => 'Inventory Deleted',
        ]);
    }

    public function index($id, Request $request): JsonResponse
    {
        $this->repository->setUser(auth()->user());

        $request->merge(['business' => $id]);

        $request->validate([
            'business' => ['required', 'exists:App\Models\Business,id'],
        ]);

        $data = $this->repository->getAllByParams([
            'business_id' => $id,
            'is_store_front' => true,
        ]);

        return jsonResponse(Response::HTTP_OK, $data->all());
    }

    public function view($inventory, $business, Request $request): JsonResponse
    {
        $this->repository->setUser(auth()->user());

        $request->merge([
            'inventory' => $inventory,
            'business' => $business,
        ]);

        $request->validate([
            'business' => ['required', 'exists:App\Models\Business,id'],
            'inventory' => ['required', 'exists:App\Models\Inventory,id'],
        ]);

        $data = $this->repository->getDetailsByParams([
            'id' => $inventory,
            'business_id' => $business,
            'is_store_front' => true,
        ]);

        return jsonResponse(Response::HTTP_OK, $data);
    }

    public function edit($id, Request $request): JsonResponse
    {
        $this->repository->setUser(auth()->user());

        $request->merge([
            'product_name' => $request->name,
            'inventory' => $id,
        ]);

        $request->validate([
            'inventory' => ['required', 'exists:App\Models\Inventory,id'],
            'product_name' => ['nullable', 'min:2'],
            'amount' => ['nullable'],
            'image' => ['nullable', 'url'],
            'stock_status' => ['nullable', 'boolean'],
            'description' => ['nullable'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        if (! $this->repository->update($id, $request->only($this->requestKeysFilterUpdate))) {
            return jsonResponse(Response::HTTP_BAD_REQUEST, [
                'message' => 'Failed to update inventory',
            ]);
        }

        return jsonResponse(Response::HTTP_OK, [
            'message' => 'Inventory Updated',
        ]);
    }
}
