<?php

namespace Src\Accounting\App\Http;

use App\Http\Controllers\DomainBaseCtrl;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Src\Accounting\App\Requests\CreateBusinessInformationRequest;
use Src\Accounting\Domain\Repository\Interfaces\ClientRepositoryInterface;
use Symfony\Component\HttpFoundation\Response;

class ClientCtrl extends DomainBaseCtrl
{
    private $repository;

    public function __construct(ClientRepositoryInterface $repository)
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
        $request->merge(['fullname' => $request->name]);

        $request->validate([
            'name'          => ['required','min:2'],
            'phone_number'  => ['required','min:11'],
            'address'       => ['required','min:3'],
            'state_id'      => ['required','exists:App\Models\State,id'],
            'zip_code'      => ['nullable','string'],
            'business_id'   => ['required','exists:App\Models\Business,id']
        ]);

        $keys = ['fullname','phone_number','address','zip_code','business_id','state_id'];

        $customerExists =  $this->repository->getDetailsByParams([
            'business_id' => $request->business_id,
            'phone_number' => $request->phone_number,
        ]);

        if(is_null($customerExists)){
            $customerExists = $this->repository->create($request->only($keys));
        }

        return jsonResponse(Response::HTTP_OK, $customerExists);
    }

    public function view(Request $request): JsonResponse
    {
        $this->repository->setUser(auth()->user());

        $request->validate([
            'client_id'      => ['required','exists:App\Models\Client,id'],
        ]);

        $customerExists =  $this->repository->getDetailsByParams([
            'id' => $request->client_id
        ]);

        if(is_null($customerExists)){
            return jsonResponse(Response::HTTP_PRECONDITION_FAILED, [
                'message' => 'We could not find what you were looking for.'
            ]);
        }

        return jsonResponse(Response::HTTP_OK, $customerExists);
    }
}
