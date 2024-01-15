<?php

namespace Src\Accounting\App\Http;

use App\Http\Controllers\DomainBaseCtrl;
use Exception;
use Illuminate\Http\JsonResponse;
use Src\Accounting\App\Requests\CreateBusinessInformationRequest;
use Src\Accounting\Domain\Repository\Interfaces\BusinessRepositoryInterface;
use Src\Accounting\Domain\Repository\Interfaces\ClientRepositoryInterface;
use Symfony\Component\HttpFoundation\Response;

class BusinessInformationCtrl extends DomainBaseCtrl
{
    private $repository;

    public function __construct(BusinessRepositoryInterface $repository)
    {
        $this->repository = $repository;
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

        $data = $this->repository->create(
            $request->only(['email','fullname','logo','phone_number','address','state_id','zip_code'])
        );

        return jsonResponse(Response::HTTP_OK, $data);
    }
}
