<?php

namespace Src\Accounting\App\Http;

use App\Http\Controllers\DomainBaseCtrl;
use Exception;
use Illuminate\Http\JsonResponse;
use Src\Accounting\App\Requests\CreateBusinessInformationRequest;
use Src\Accounting\Domain\Repository\Interfaces\ClientRepositoryInterface;
use Symfony\Component\HttpFoundation\Response;

class BusinessInformationCtrl extends DomainBaseCtrl
{
    private $repository;

    public function __construct(BusinessRepositoryInterface $businesRepository)
    {
        $this->repository = $businesRepository;
        parent::__construct();
    }
    /**
     * @throws Exception
     */
    public function store(CreateBusinessInformationRequest $request): JsonResponse
    {
        $request->merge([
            'fullname' => $request->name,
        ]);
        $request->validated();
        //create a business information from the api data
        return jsonResponse(Response::HTTP_OK, $this->customer->load('profile'));
    }
}
