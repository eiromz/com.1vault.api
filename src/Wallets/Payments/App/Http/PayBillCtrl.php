<?php

namespace Src\Wallets\Payments\App\Http;

use App\Exceptions\BaseException;
use App\Exceptions\InsufficientBalance;
use App\Http\Controllers\DomainBaseCtrl;
use Illuminate\Http\JsonResponse;
use Src\Wallets\Payments\App\Requests\PayBillRequest;
use Src\Wallets\Payments\Domain\Actions\GetAccountInstance;
use Src\Wallets\Payments\Domain\Services\JournalWalletDebitService;
use Symfony\Component\HttpFoundation\Response;

class PayBillCtrl extends DomainBaseCtrl
{
    //validate pin sent via the api
    //validate the account balance of the customer
    //debit the account
    //store bills data for processing
    //dispatch bills data for creating the daily limit
    //Also create the daily limit for a customer's account for
    /**
     */
    public function __invoke(PayBillRequest $request): JsonResponse
    {
        $data = $request->validated();

        dd($request->all());

        //i need to make changes to the request

        //send the data to the

        //        (new JournalWalletDebitService(
        //            GetAccountInstance::getActiveInstance(auth()->user()->profile),
        //            $request
        //        ))->checkBalance()->debit('Bills')->notify()->updateBalanceQueue();

        return jsonResponse(Response::HTTP_OK, $data);
    }
}
