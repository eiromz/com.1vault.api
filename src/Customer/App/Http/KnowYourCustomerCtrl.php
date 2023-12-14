<?php

namespace Src\Customer\App\Http;

use App\Http\Controllers\DomainBaseCtrl;
use App\Models\KnowYourCustomer;
use App\Models\Profile;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Src\Customer\App\Http\Data\CompleteCustomerProfileData;
use Symfony\Component\HttpFoundation\Response;

class KnowYourCustomerCtrl extends DomainBaseCtrl
{
    /**
     * @throws Exception
     */
    public function __invoke(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'bvn'           => ['required','size:11'],
            'doc_type'      => ['required','string',Rule::in(Profile::DOC_TYPES)],
            'doc_image'     => ['required','string','url'],
            'selfie'        => ['required','string','url'],
        ]);

        $kyc = new KnowYourCustomer();
        $kyc->fill($request->only(['bvn','doc_type','doc_image','selfie']));
        $kyc->customer_id = auth()->user()->id;
        $kyc->save();

        return jsonResponse(Response::HTTP_OK, [
            'message' => 'Success We would get back to you as soon as possible.'
        ]);
    }
}
