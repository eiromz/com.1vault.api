<?php

namespace Src\Accounting\App\Http;

use App\Exceptions\BaseException;
use App\Http\Controllers\DomainBaseCtrl;
use App\Models\Business;
use App\Models\Cart;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Src\Accounting\App\Requests\CreateInventoryRequest;
use Src\Accounting\Domain\Repository\Interfaces\BusinessRepositoryInterface;
use Src\Accounting\Domain\Repository\Interfaces\InventoryRepositoryInterface;
use Symfony\Component\HttpFoundation\Response;

class StoreFrontInventoryCtrl extends DomainBaseCtrl
{
    private $repository;
    public array $requestKeysFilterCreate = [
        'amount', 'is_published', 'product_name', 'unit', 'quantity', 'business_id',
        'selling_price','is_store_front','image','stock_status','description'
    ];
    public array $requestKeysFilterUpdate = [
        'amount', 'product_name','description','image','stock_status','is_published'
    ];

    public function __construct(InventoryRepositoryInterface $repository)
    {
        $this->repository = $repository;
        parent::__construct();
    }
    public function store(Request $request): JsonResponse
    {
        $this->repository->setUser(auth()->user());

        $request->merge([
            'is_published'      => false,
            'product_name'      => $request->name,
            'business_id'       => $request->business,
            'is_store_front'    => true,
            'quantity' => 0,
            'unit' => 'pcs',
            'selling_price' => $request->amount
        ]);

        $request->validate([
            'name' => ['required', 'min:2'],
            'amount' => ['required'],
            'business' => ['required'],
            'image'   => ['required','url'],
            'stock_status' => ['required','boolean'],
            'description' => ['required']
        ]);

        $data = $this->repository->create($request->only($this->requestKeysFilterCreate));

        return jsonResponse(Response::HTTP_OK, $data);
    }
    public function destroy(Request $request): JsonResponse
    {
        $this->repository->setUser(auth()->user());

        $request->validate([
            'inventory' => ['required', 'array'],
        ]);

        $delete = [];

        foreach ($request->inventory as $inventory) {
            $delete[] = $inventory['inventory'];
        }

        if (! $this->repository->deleteByIds($delete)) {
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
            'is_store_front'=> true
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
            'is_store_front' => true
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
            'inventory'         => ['required', 'exists:App\Models\Inventory,id'],
            'product_name'      => ['nullable', 'min:2'],
            'amount'            => ['nullable'],
            'image'             => ['nullable','url'],
            'stock_status'      => ['nullable','boolean'],
            'description'       => ['nullable'],
            'is_published'      => ['nullable','boolean']
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
