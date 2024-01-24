<?php

namespace Src\Accounting\App\Http;

use App\Http\Controllers\DomainBaseCtrl;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Src\Accounting\Domain\Repository\Interfaces\ClientRepositoryInterface;
use Symfony\Component\HttpFoundation\Response;

class ClientCtrl extends DomainBaseCtrl
{
    private $repository;

    public array $requestKeysFilter = ['fullname', 'phone_number', 'address', 'zip_code', 'business_id', 'state_id'];

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
            'name' => ['required', 'min:2'],
            'phone_number' => ['required', 'min:11'],
            'address' => ['required', 'min:3'],
            'state_id' => ['required', 'exists:App\Models\State,id'],
            'zip_code' => ['nullable', 'string'],
            'business_id' => ['required', 'exists:App\Models\Business,id'],
        ]);

        $customerExists = $this->repository->create($request->only($this->requestKeysFilter));

        return jsonResponse(Response::HTTP_OK, $customerExists);
    }

    public function view(Request $request): JsonResponse
    {
        $this->repository->setUser(auth()->user());

        $request->validate([
            'client_id' => ['required', 'exists:App\Models\Client,id'],
        ]);

        $customerExists = $this->repository->getDetailsByParams([
            'id' => $request->client_id,
        ]);

        if (is_null($customerExists)) {
            return jsonResponse(Response::HTTP_PRECONDITION_FAILED, [
                'message' => 'We could not find what you were looking for.',
            ]);
        }

        return jsonResponse(Response::HTTP_OK, $customerExists);
    }

    public function index($id): JsonResponse
    {
        $this->repository->setUser(auth()->user());

        $data = $this->repository->getAllByParams([
            'business_id' => $id,
        ]);

        return jsonResponse(Response::HTTP_OK, $data);
    }

    public function update($id, Request $request): JsonResponse
    {
        $this->repository->setUser(auth()->user());

        $request->merge([
            'client' => $id,
            'fullname' => $request->name,
        ]);

        $request->validate([
            'name' => ['nullable', 'min:2'],
            'phone_number' => ['nullable', 'min:11'],
            'address' => ['nullable', 'min:3'],
            'state_id' => ['nullable', 'exists:App\Models\State,id'],
            'zip_code' => ['nullable', 'string'],
            'client' => ['required', 'exists:App\Models\Client,id'],
        ]);

        if (! $this->repository->update($id, $request->only($this->requestKeysFilter))) {
            return jsonResponse(Response::HTTP_BAD_REQUEST, [
                'message' => 'Failed to update customer',
            ]);
        }

        return jsonResponse(Response::HTTP_OK, [
            'message' => 'Customer updated',
        ]);
    }

    public function destroy(Request $request): JsonResponse
    {
        $this->repository->setUser(auth()->user());

        $request->validate([
            'customer' => ['required', 'exists:App\Models\Client,id'],
        ]);

        if (! $this->repository->delete($request->business)) {
            return jsonResponse(Response::HTTP_BAD_REQUEST, [
                'message' => 'Failed to Delete Customer',
            ]);
        }

        return jsonResponse(Response::HTTP_OK, [
            'message' => 'Customer Deleted',
        ]);
    }
}
