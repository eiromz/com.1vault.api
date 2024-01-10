<?php

namespace Src\Wallets\Payments\App\Http;

use App\Http\Controllers\DomainBaseCtrl;
use App\Jobs\AccountBalanceUpdateQueue;
use App\Jobs\SendFireBaseNotificationQueue;
use App\Models\Journal;
use App\Models\Profile;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Src\Wallets\Payments\Domain\Actions\CreditJournalAction;
use Src\Wallets\Payments\Domain\Actions\GetAccountInstance;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class ProvidusWebhookCtrl extends DomainBaseCtrl
{
    /**
     * @throws Exception
     */
    public function __invoke(Request $request): JsonResponse
    {
        $request->validate([
            'accountNumber' => ['required', 'exists:App\Models\Profile,account_number'],
        ]);

        $profile = Profile::query()
            ->where('account_number', '=', $request->accountNumber)
            ->with('customer')
            ->firstOrFail();

        $journal = Journal::query()->where('trx_ref', '=', $request->settlementId);

        if ($journal->exists()) {
            return response()->json([
                'sessionId' => $request->sessionId,
                'requestSuccessful' => true,
                'responseMessage' => 'duplicate transaction',
                'responseCode' => '01',
            ], ResponseAlias::HTTP_OK);
        }

        $account = GetAccountInstance::getActiveInstance($profile);

        $newJournalBalance = CreditJournalAction::execute($request, $account, 'webhook');

        $notification = [
            'title' => 'Credit Notification',
            'body' => 'Wallet Credit Notification',
        ];

        if (! is_null($profile->customer->firebase_token)) {
            SendFireBaseNotificationQueue::dispatch($profile->customer->firebase_token, $notification);
        }

        AccountBalanceUpdateQueue::dispatch(
            $newJournalBalance->balance_before, $newJournalBalance->balance_after, $account);
        //TODO send email notification for when account is credited.

        return response()->json([
            'requestSuccessful' => true,
            'sessionId' => $request->sessionId,
            'responseMessage' => 'success',
            'responseCode' => '00',
        ], ResponseAlias::HTTP_OK);
    }

    //Airtime::purchase();
    //Debit::journal();
    //Subscribe::service()
    //Order::service()
    //ProcessService::save();
    //PosRequest::save();
}
