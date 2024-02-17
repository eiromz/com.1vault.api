<?php

namespace Src\Accounting\App\Http;

use App\Exceptions\BaseException;
use App\Http\Controllers\DomainBaseCtrl;
use App\Models\Business;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Src\Accounting\App\Requests\CreateBusinessInformationRequest;
use Src\Accounting\Domain\Repository\Interfaces\BusinessRepositoryInterface;
use Symfony\Component\HttpFoundation\Response;

class BusinessInformationCtrl extends DomainBaseCtrl
{
    private $repository;

    private array $filterRequestKeys = ['email', 'fullname', 'logo', 'phone_number', 'address', 'state_id', 'zip_code'];

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

        $customerBusinessExists = Business::query()
            ->where('customer_id', '=', auth()->user()->id)
            ->where('is_store_front', '=', false)
            ->exists();

        if ($customerBusinessExists) {
            throw new BaseException('Business already exists for this account', Response::HTTP_BAD_REQUEST);
        }

        $request->validated();

        $data = $this->repository->create($request->only($this->filterRequestKeys));

        return jsonResponse(Response::HTTP_OK, $data);
    }

    public function view(Request $request): JsonResponse
    {
        $this->repository->setUser(auth()->user());

        $request->validate([
            'business' => ['required', 'exists:App\Models\Business,id'],
        ]);

        $data = $this->repository->getDetailsByParams([
            'id' => $request->business,
            'is_store_front' => false,
        ]);

        return jsonResponse(Response::HTTP_OK, $data);
    }

    public function index(): JsonResponse
    {
        $this->repository->setUser(auth()->user());

        return jsonResponse(Response::HTTP_OK, $this->repository->getAllByParams([]));
    }

    public function update($id, Request $request): JsonResponse
    {
        $this->repository->setUser(auth()->user());

        $request->merge([
            'business' => $id,
            'fullname' => $request->name,
        ]);

        $request->validate([
            'name' => ['nullable', 'min:2'],
            'phone_number' => ['nullable', 'min:11'],
            'address' => ['nullable', 'min:3'],
            'state_id' => ['nullable', 'exists:App\Models\State,id'],
            'zip_code' => ['nullable', 'string'],
            'logo' => ['nullable', 'url'],
            'business' => ['required', 'exists:App\Models\Business,id'],
        ]);

        if (! $this->repository->update($id, $request->only($this->filterRequestKeys))) {
            return jsonResponse(Response::HTTP_BAD_REQUEST, [
                'message' => 'Failed to update business',
            ]);
        }

        return jsonResponse(Response::HTTP_OK, [
            'message' => 'Business updated',
        ]);
    }

    public function destroy(Request $request): JsonResponse
    {
        $this->repository->setUser(auth()->user());

        $request->validate([
            'business' => ['required', 'exists:App\Models\Business,id'],
        ]);

        if (! $this->repository->delete($request->business)) {
            return jsonResponse(Response::HTTP_BAD_REQUEST, [
                'message' => 'Failed to Delete Business',
            ]);
        }

        return jsonResponse(Response::HTTP_OK, [
            'message' => 'Business Deleted',
        ]);
    }
}
