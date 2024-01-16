<?php

namespace Src\Accounting\App\Http;

use App\Http\Controllers\DomainBaseCtrl;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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
        $this->repository->setUser(auth()->user());
        $request->merge([
            'fullname' => $request->name,
        ]);
        $request->validated();

        $data = $this->repository->create(
            $request->only(['email','fullname','logo','phone_number','address','state_id','zip_code'])
        );

        return jsonResponse(Response::HTTP_OK, $data);
    }

    public function view(Request $request)
    {
        $this->repository->setUser(auth()->user());

        $request->validate([
            'business' => ['required','exists:App\Models\Business,id']
        ]);

        $data =  $this->repository->getDetailsByParams([
            'id' => $request->business
        ]);

        return jsonResponse(Response::HTTP_OK, $data);
    }
}
