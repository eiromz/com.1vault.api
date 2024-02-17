<?php

namespace Src\Merchant\App\Http;

use App\Http\Controllers\DomainBaseCtrl;
use App\Models\KnowYourCustomer;
use App\Models\Profile;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class KnowYourCustomerCtrl extends DomainBaseCtrl
{
    /**
     * @throws Exception
     */
    public function __invoke(Request $request): JsonResponse
    {
        $request->validate([
            'bvn' => ['required', 'size:11'],
            'doc_type' => ['required', 'string', Rule::in(Profile::DOC_TYPES)],
            'doc_image' => ['required', 'string', 'url'],
            'selfie' => ['required', 'string', 'url'],
            'utility_bill' => ['required', 'string', 'url'],
        ]);

        $request->merge([
            'bvn' => base64_encode($request->bvn),
        ]);

        if (is_null(auth()->user()->image)) {
            $request->user()->fill([
                'image' => $request->selfie,
            ]);

            $request->user()->save();
        }

        $kyc = new KnowYourCustomer();
        $kyc->fill($request->only(['bvn', 'doc_type', 'doc_image', 'selfie', 'utility_bill']));
        $kyc->customer_id = $request->user()->id;
        $kyc->save();

        return jsonResponse(Response::HTTP_OK, [
            'message' => 'Success We would get back to you as soon as possible.',
        ]);
    }
}
