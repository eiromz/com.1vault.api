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

    public function __construct(ClientRepositoryInterface $clientRepository)
    {
        $this->repository = $clientRepository;
        parent::__construct();
    }
    /**
     * @throws Exception
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name'          => ['required','min:2'],
            'phone_number'  => ['required','min:11'],
            'email'         => ['required','email','unique:App\Models\Business,email'],
            'address'       => ['required','min:3'],
            'state_id'      => ['required','exists:App\Models\State,id'],
            'zip_code'      => ['required','string'],
            'logo'          => ['required','url'],
        ]);

        dd($request->all());
        //create a business information from the api data
        return jsonResponse(Response::HTTP_OK, $this->customer->load('profile'));
    }
}
