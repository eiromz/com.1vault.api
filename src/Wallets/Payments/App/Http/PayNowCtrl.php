<?php

namespace Src\Wallets\Payments\App\Http;

use App\Http\Controllers\DomainBaseCtrl;
use App\Jobs\ProcessCartQueue;
use App\Models\Cart;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Src\Wallets\Payments\Domain\Actions\GetAccountInstance;
use Src\Wallets\Payments\Domain\Services\JournalWalletDebitService;
use Symfony\Component\HttpFoundation\Response;

class PayNowCtrl extends DomainBaseCtrl
{
    /**
     * @throws Exception
     */
    public function store(Request $request): JsonResponse
    {
        //expand pay now for bills
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

        $request->merge([
            'trx_ref' => $source->journal->trx_ref,
            'order_number' => generateOrderNumber(),
        ]);

        $carts = Cart::query()
            ->where('customer_id', '=', auth()->user()->id)
            ->where('account_id', '=', auth()->user()->ACCOUNTID)
            ->with('service')
            ->whereNull('order_number')
            ->get();

        ProcessCartQueue::dispatch($carts, auth()->user(), $request->all());

        return jsonResponse(Response::HTTP_OK, $source->journal);
    }
}
