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
use Illuminate\Support\Facades\Validator;
use Src\Wallets\Payments\Domain\Actions\CreditJournalAction;
use Src\Wallets\Payments\Domain\Actions\GetAccountInstance;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class WebhookCtrl extends DomainBaseCtrl
{
    public array $notification = [
        'title' => 'Credit Notification',
        'body' => 'Wallet Credit Notification',
    ];

    /**
     * @throws Exception
     */
    public function __invoke(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'accountNumber' => ['required', 'exists:App\Models\Profile,account_number'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'sessionId' => $request->sessionId,
                'requestSuccessful' => true,
                'responseMessage' => 'rejected transaction',
                'responseCode' => '02',
            ], ResponseAlias::HTTP_OK);
        }

        $journal = Journal::query()->where('trx_ref', '=', $request->settlementId)->exists();

        if ($journal) {
            return response()->json([
                'sessionId' => $request->sessionId,
                'requestSuccessful' => true,
                'responseMessage' => 'duplicate transaction',
                'responseCode' => '01',
            ], ResponseAlias::HTTP_OK);
        }

        $profile = Profile::query()
            ->where('account_number', '=', $request->accountNumber)
            ->with('customer')
            ->firstOrFail();

        $account = GetAccountInstance::getActiveInstance($profile);

        $newJournalBalance = CreditJournalAction::execute($request, $account, 'webhook');

        SendFireBaseNotificationQueue::dispatch($profile->customer->firebase_token ?? null, $this->notification);

        //Send email notification of credit

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

    private function duplicateTransaction()
    {

    }

    //Airtime::purchase();
    //Debit::journal();
    //Subscribe::service()
    //Order::service()
    //ProcessService::save();
    //PosRequest::save();
}
