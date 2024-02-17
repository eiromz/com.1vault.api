<?php

namespace Src\Accounting\App\Http;

use App\Exceptions\BaseException;
use App\Http\Controllers\DomainBaseCtrl;
use App\Models\Business;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Src\Accounting\Domain\Repository\Interfaces\BusinessRepositoryInterface;
use Symfony\Component\HttpFoundation\Response;

class StoreFrontCtrl extends DomainBaseCtrl
{
    private $repository;

    private array $createRequestkeys = [
        'email', 'fullname', 'logo', 'phone_number', 'address', 'state_id',
        'zip_code', 'trx_ref', 'is_store_front', 'customer_id', 'whatsapp_number',
        'trx_ref', 'sector', 'facebook', 'instagram', 'twitter_x',
    ];

    public function __construct(BusinessRepositoryInterface $repository)
    {
        $this->repository = $repository;
        parent::__construct();
    }

    /**
     * @throws BaseException
     */
    public function store(Request $request): JsonResponse
    {

        //subscription is created when payment is made
        //check if a payment or subscription for front exists and fetch it from subscription table.

        $this->storeFrontExists();
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
            'logo' => ['required', 'url'],
            'sector' => ['required', 'string'],
            'trx_ref' => ['required', 'exists:App\Models\Journal,trx_ref'],
            'whatsapp_number' => ['required'],
            'facebook' => ['nullable'],
            'instagram' => ['nullable'],
            'twitter_x' => ['nullable'],
        ]);

        $data = Business::query()->create($request->only($this->createRequestkeys));

        return jsonResponse(Response::HTTP_OK, $data);
    }

    private function storeFrontExists(): void
    {
        $storeFrontExists = Business::query()
            ->where('customer_id', '=', auth()->user()->id)
            ->where('is_store_front', '=', true)
            ->exists();

        if ($storeFrontExists) {
            throw new BaseException('Store Front already exists for this account', Response::HTTP_BAD_REQUEST);
        }
    }
}
