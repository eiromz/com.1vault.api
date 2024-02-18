<?php

namespace Src\Wallets\Payments\App\Http;

use App\Http\Controllers\DomainBaseCtrl;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Src\Wallets\Payments\Domain\Actions\GetAccountInstance;
use Src\Wallets\Payments\Domain\Services\JournalWalletDebitService;
use Src\Wallets\Payments\Domain\Services\ProcessCartTransaction;
use Symfony\Component\HttpFoundation\Response;

class PayNowCtrl extends DomainBaseCtrl
{
    /**
     * @throws Exception
     */
    public function store(Request $request): JsonResponse
    {
        $request->merge([
            'source' => 'wallet',
            'amount' => $request->total,
        ]);

        $request->validate([
            'total' => ['required'],
            'transaction_pin' => ['required'],
        ]);

        $source = new JournalWalletDebitService(
            GetAccountInstance::getActiveInstance(auth()->user()->profile),
            $request
        );

        $source->validateTransactionPin();

        $source->checkBalance()->debit()->notify()->updateBalanceQueue();

        $request->merge(['journal' => $source->journal]);

      //loop through using cart

        //we need to create a subscription

        //$transaction = (new ProcessCartTransaction($source->request));

        //$transaction->process();

        //attach the tx_refercence to the user profile.

        return jsonResponse(Response::HTTP_OK, $source->journal);
    }
}
