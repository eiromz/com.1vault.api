<?php

namespace Src\Accounting\App\Http;

use App\Http\Controllers\DomainBaseCtrl;
use App\Models\StoreFront;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Src\Accounting\Domain\Repository\Interfaces\BusinessRepositoryInterface;
use App\Exceptions\BaseException;
use Symfony\Component\HttpFoundation\Response;

class StoreFrontCtrl extends DomainBaseCtrl
{
    private $repository;

    private array $createRequestkeys = [
        'email', 'fullname', 'image', 'phone_number', 'address', 'state_id',
        'zip_code', 'is_store_front', 'customer_id', 'whatsapp_number',
        'sector', 'facebook', 'instagram', 'twitter_x',
    ];

    private array $updateRequestkeys = [
        'email', 'fullname', 'image', 'phone_number', 'address', 'state_id',
        'zip_code', 'whatsapp_number', 'sector', 'facebook', 'instagram', 'twitter_x',
    ];

    public function __construct(BusinessRepositoryInterface $repository)
    {
        $this->repository = $repository;
        parent::__construct();
    }

    public function index()
    {
        $storeFrontExists = StoreFront::query()
            ->where('customer_id', '=', auth()->user()->id)
            ->where('is_store_front', '=', true)->firstOrFail();

        return jsonResponse(Response::HTTP_OK, $storeFrontExists);
    }

    /**
     * @throws BaseException
     */
    public function store(Request $request): JsonResponse
    {
        $request->merge([
            'customer_id' => auth()->user()->id,
            'is_store_front' => true,
            'fullname' => $request->name,
        ]);

        $request->validate([
            'name' => ['required', 'min:2'],
            'phone_number' => ['required', 'min:11'],
            'email' => ['required', 'email', 'unique:App\Models\Business,email'],
            'address' => ['required', 'min:3'],
            'state_id' => ['required', 'exists:App\Models\State,id'],
            'image' => ['required', 'url'],
            'sector' => ['required', 'string'],
            'whatsapp_number' => ['required'],
            'facebook' => ['nullable'],
            'instagram' => ['nullable'],
            'twitter_x' => ['nullable'],
        ]);

        $storeFrontExists = StoreFront::query()
            ->where('customer_id', '=', auth()->user()->id)
            ->where('is_store_front', '=', true)->get();

        if ($storeFrontExists->isEmpty()) {
            $storeFrontExists = StoreFront::query()->create($request->only($this->createRequestkeys));
        }

        return jsonResponse(Response::HTTP_OK, $storeFrontExists);
    }

    public function update($storefront, Request $request): JsonResponse
    {
        $request->merge([
            'storefront' => $storefront,
            'fullname' => $request->name,
        ]);

        $request->validate([
            'storefront' => ['required', 'exists:App\Models\Business,id'],
            'name' => ['nullable', 'min:2'],
            'phone_number' => ['nullable', 'min:11'],
            'email' => ['nullable', 'email', 'unique:App\Models\Business,email'],
            'address' => ['nullable', 'min:3'],
            'state_id' => ['nullable'],
            'image' => ['nullable', 'url'],
            'sector' => ['nullable', 'string'],
            'whatsapp_number' => ['nullable'],
            'facebook' => ['nullable'],
            'instagram' => ['nullable'],
            'twitter_x' => ['nullable'],
        ]);

        $data = StoreFront::query()->findOrFail($storefront);

        $data->fill($request->only($this->updateRequestkeys))->save();

        return jsonResponse(Response::HTTP_OK, $data);
    }
}
