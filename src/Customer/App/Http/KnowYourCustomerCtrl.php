<?php

namespace Src\Customer\App\Http;

use App\Http\Controllers\DomainBaseCtrl;
use Exception;
use Illuminate\Http\Request;
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
            'doc_type'      => ['required','string'],
            'doc_image'     => ['required','string','url'],
            'selfie'        => ['required','string','url'],
        ]);

        //once this has been validated upload to the know your customer table
        //customers are added using the accountid


        return jsonResponse(Response::HTTP_OK, $this->customer->load('profile'));
    }
}
