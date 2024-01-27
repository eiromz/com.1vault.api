<?php

namespace Src\Wallets\Payments\App\Http;

use App\Http\Controllers\DomainBaseCtrl;
use App\Models\Profile;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Src\Wallets\Payments\Domain\Actions\GetAccountInstance;
use Symfony\Component\HttpFoundation\Response;

class WalletAccountSearchCtrl extends DomainBaseCtrl
{
    /**
     * @throws Exception
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'account_number' => ['required', 'exists:App\Models\Profile,account_number'],
            ]);

            $profile = Profile::query()
                ->where('customer_id', '<>', auth()->user()->id)
                ->where('account_number', '=', $request->account_number)
                ->with('customer')
                ->firstOrFail();

            $account = GetAccountInstance::getActiveInstance(auth()->user()->profile);

            $collection = collect([
                'account_balance' => $account->balance_after,
                'account_infomation' => $profile->only(['firstname', 'lastname', 'account_number']),
            ]);

            return jsonResponse(Response::HTTP_OK, $collection);

        } catch (Exception $e) {
            return jsonResponse(Response::HTTP_BAD_REQUEST, ['message' => 'Invalid Account Number']);
        }
    }

    //Airtime::purchase();
    //Debit::journal();
    //Subscribe::service()
    //Order::service()
    //ProcessService::save();
    //PosRequest::save();
}
