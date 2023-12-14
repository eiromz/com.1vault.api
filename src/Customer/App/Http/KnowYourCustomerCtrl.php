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
        $kyc->fill();


        return jsonResponse(Response::HTTP_OK, $this->customer->load('profile'));
    }
}
