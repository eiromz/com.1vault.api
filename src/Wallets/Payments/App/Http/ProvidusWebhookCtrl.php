<?php

namespace Src\Wallets\Payments\App\Http;

use App\Http\Controllers\DomainBaseCtrl;
use App\Jobs\SendFireBaseNotificationQueue;
use App\Models\Journal;
use App\Models\Profile;
use App\Support\Firebase;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Kreait\Firebase\Exception\FirebaseException;
use Kreait\Firebase\Exception\MessagingException;
use Src\Wallets\Payments\Domain\Actions\CreditJournalAction;
use Src\Wallets\Payments\Domain\Actions\GetAccountInstance;
use Src\Wallets\Payments\Domain\Services\CreditEntryJournalService;
use Src\Wallets\Payments\Domain\Services\ProvidusCreditService;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class ProvidusWebhookCtrl extends DomainBaseCtrl
{
    /**
     * @throws Exception
     */
    public function __invoke(Request $request): JsonResponse
    {
        $request->validate([
            'accountNumber' => ['required','exists:App\Models\Profile,account_number']
        ]);

        $profile = Profile::query()
            ->where('account_number','=',$request->accountNumber)
            ->with('customer')
            ->firstOrFail();

        $journal = Journal::query()
            ->where('trx_ref','=',$request->settlementId)
            ->exists();

        if($journal) {
            return jsonResponse(ResponseAlias::HTTP_OK, [
                "requestSuccessful" => true,
                "sessionId" => $request->sessionId,
                "responseMessage" => "duplicate transaction",
                "responseCode" => "01"
            ]);
        }

        $account = GetAccountInstance::getActiveInstance($profile);

        $journal = CreditJournalAction::execute($request,$account,'webhook');

        //TODO send email notification for when account is credited.
        //TODO send Firebase notification

        $notification = [
            'title' => 'Credit Notification',
            'body' => 'New credit to your account.'
        ];

        SendFireBaseNotificationQueue::dispatch($profile->customer->firebase_token, $notification)->delay(now()->addMinute());

        return jsonResponse(ResponseAlias::HTTP_OK,[
            "requestSuccessful" => true,
            "sessionId"         => $request->sessionId,
            "responseMessage"   => "success",
            "responseCode"      => "00",
            "journal"           => $journal
        ]);
    }

    //Airtime::purchase();
    //Debit::journal();
    //Subscribe::service()
    //Order::service()
    //ProcessService::save();
    //PosRequest::save();
}
