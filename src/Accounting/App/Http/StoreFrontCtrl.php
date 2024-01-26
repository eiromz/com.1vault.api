<?php

namespace Src\Accounting\App\Http;

use App\Http\Controllers\DomainBaseCtrl;
use App\Models\Business;
use App\Models\Cart;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Src\Accounting\Domain\Repository\Interfaces\BusinessRepositoryInterface;
use Symfony\Component\HttpFoundation\Response;

class StoreFrontCtrl extends DomainBaseCtrl
{
    private $repository;

    private array $createRequestkeys = [
        'email', 'fullname', 'logo', 'phone_number', 'address', 'state_id',
        'zip_code','trx_reference','is_store_front','customer_id','whatsapp_number',
        'trx_reference','category'
    ];

    public function __construct(BusinessRepositoryInterface $repository)
    {
        $this->repository = $repository;
        parent::__construct();
    }
    public function store(Request $request): JsonResponse
    {
        $request->merge([
            'customer_id' => auth()->user()->id,
            'is_store_front' => true,
            'fullname' => $request->name
        ]);

        $request->validate([
            'name' => ['required', 'min:2'],
            'phone_number' => ['required', 'min:11'],
            'email' => ['required', 'email', 'unique:App\Models\Business,email'],
            'address' => ['required', 'min:3'],
            'state_id' => ['required', 'exists:App\Models\State,id'],
            'zip_code' => ['required', 'string'],
            'logo' => ['required', 'url'],
            'category' => ['required', 'string'],
            'trx_reference' => ['required'],
            'whatsapp_number' => ['required'],
            'facebook' => ['nullable'],
            'instagram' => ['nullable'],
            'twitter_x' => ['nullable'],
        ]);

        $data = Business::query()->create($request->only($this->createRequestkeys));

        return jsonResponse(Response::HTTP_OK, $data);
    }
}
