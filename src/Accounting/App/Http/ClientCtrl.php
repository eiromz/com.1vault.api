<?php

namespace Src\Accounting\App\Http;

use App\Http\Controllers\DomainBaseCtrl;
use Exception;
use Illuminate\Http\JsonResponse;
use Src\Accounting\App\Requests\CreateBusinessInformationRequest;
use Src\Accounting\Domain\Repository\Interfaces\ClientRepositoryInterface;
use Symfony\Component\HttpFoundation\Response;

class ClientCtrl extends DomainBaseCtrl
{
    private $repository;

    public function __construct(ClientRepositoryInterface $clientRepository)
    {
        $this->repository = $clientRepository;
    }
    /**
     * @throws Exception
     */
    public function create(Request $request): JsonResponse
    {
        $request->validate([
            'business_name' => ['required'],
            'email_address'
        ]);
        //create a business information from the api data
        return jsonResponse(Response::HTTP_OK, $this->customer->load('profile'));
    }
}
