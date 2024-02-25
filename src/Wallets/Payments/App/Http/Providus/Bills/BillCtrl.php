<?php

namespace Src\Wallets\Payments\App\Http\Providus\Bills;

use App\Exceptions\BaseException;
use App\Http\Controllers\DomainBaseCtrl;
use JsonException;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;
use Illuminate\Http\JsonResponse;
use Src\Wallets\Payments\Domain\Integrations\Providus\ProvidusBills;
use Src\Wallets\Payments\Domain\Integrations\Providus\Requests\GetFieldsForBills;

class BillCtrl extends DomainBaseCtrl
{
    /**
     * @throws FatalRequestException
     * @throws BaseException
     * @throws RequestException
     * @throws JsonException
     */
    public function index($bill): JsonResponse
    {

        $connector  = new ProvidusBills();
        $request    = new GetFieldsForBills($bill);
        $response   = $connector->send($request);

        dd($response->collect()->get('fields'));

        if($response->collect()->pluck('type')){
            //return
        }

        //call the api here
        //pass the collection data into the bill
        //confirm if the bill is static or dynamic
        //check if the validation is a yes or no

        dd($response->json());

        return jsonResponse($response->status(),$data);
    }

}
