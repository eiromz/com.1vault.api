<?php

namespace Src\Wallets\Payments\App\Http;

use App\Http\Controllers\DomainBaseCtrl;
use App\Models\Cart;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Src\Wallets\Payments\Domain\Actions\GetAccountInstance;
use Src\Wallets\Payments\Domain\Services\JournalWalletDebitService;
use Src\Wallets\Payments\Domain\Services\ProcessServiceTransaction;
use Symfony\Component\HttpFoundation\Response;

class PayNowCtrl extends DomainBaseCtrl
{
    /**
     * @throws Exception
     */
    public function store(Request $request): JsonResponse
    {
       try{
           $request->validate([
               'total' => ['required'],
               'cart' => ['nullable'],
               'transaction_pin' => ['required']
           ]);


           $source = new JournalWalletDebitService(
               GetAccountInstance::getActiveInstance(auth()->user()->profile),
               $request
           );

           dd($source);

           $process = new ProcessServiceTransaction($request,$transaction);

           return jsonResponse(Response::HTTP_OK, $data);
       }catch (Exception $e) {
           logExceptionErrorMessage('PayNowCtrl');
           return jsonResponse(Response::HTTP_OK, $data);
       }
    }
}
