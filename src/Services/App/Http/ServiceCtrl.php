<?php

namespace Src\Services\App\Http;

use App\Http\Controllers\DomainBaseCtrl;
use App\Models\Service;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Src\Services\App\Http\Requests\ServiceRequest;
use Symfony\Component\HttpFoundation\Response;

class ServiceCtrl extends DomainBaseCtrl
{
    public function index(Request $request)
    {
        $request->validate([
            'category' => ['required'],
        ]);

        $service = Service::query()
            ->where('category', '=', $request->category)
            ->get();

        return jsonResponse(Response::HTTP_OK, $service);
    }

    /**
     * @throws Exception
     */
    public function store(ServiceRequest $request): JsonResponse
    {
        try {
            $request->merge([
                'customer_id' => auth()->user()->id,
            ]);

            $request->validated();

            $data = $request->getModel()->create($request->getOnly());

            return jsonResponse(Response::HTTP_OK, $data);
        } catch (Exception $e) {
            logExceptionErrorMessage('ServiceCtrl', $e);

            return jsonResponse(Response::HTTP_BAD_REQUEST, [
                'message' => 'Failed to create pos request',
            ]);
        }
    }
}
