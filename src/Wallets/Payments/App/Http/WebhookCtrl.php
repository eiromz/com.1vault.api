<?php

namespace Src\Wallets\Payments\App\Http;

use App\Http\Controllers\DomainBaseCtrl;
use App\Jobs\AccountBalanceUpdateQueue;
use App\Jobs\SaveProvidusWebhookDataStore;
use App\Jobs\SendFireBaseNotificationQueue;
use App\Models\Journal;
use App\Models\Profile;
use App\Models\ProvidusDataStore;
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

    private array $response_base = [
        'requestSuccessful' => true,
        'responseMessage' => 'success',
        'responseCode' => '00',
    ];

    public function index(Request $request): JsonResponse
    {
        $this->response_base['sessionId'] = $request->sessionId;

        //add this to a class and throw an exception if it fails to find the information
        $queries = [
            'userExists' => Profile::query()->where('account_number', $request->account_number)->exists(),
            'settlementIdExists' => ProvidusDataStore::query()->where('settlement_id', $request->settlementId)->exists(),
        ];

        if ($queries['settlementIdExists']) {
            $this->response_base = $this->modifyResponse($this->response_base, '01', 'duplicate transaction');
        }

        if (! $queries['userExists']) {
            $this->response_base = $this->modifyResponse($this->response_base, '02', 'rejected transaction');
        }

        SaveProvidusWebhookDataStore::dispatch($this->modifyRequestData($request->all()))->delay(now()->addMinutes(2));

        return response()->json($this->response_base, ResponseAlias::HTTP_OK);
    }

    protected function modifyRequestData($request): array
    {
        return [
            'session_id' => $request['sessionId'],
            'settlement_id' => $request['settlementId'],
            'payload' => json_encode($request),
            'account_number' => $request['accountNumber'],
            'processed' => false,
        ];
    }

    private function modifyResponse($array, $code, $message)
    {
        $array['responseCode'] = $code;
        $array['responseMessage'] = $message;

        return $array;
    }

    /**
     * @throws Exception
     */
    public function indexxx(Request $request): JsonResponse
    {
        //verify the headers
        // payload:jsonb, processed: boolean,true|false,
        //save every transaction to the database and process it after once it is processed fund the persons account
        //create a separate name job to handle all transactions for a customer

        dd($request->all());

        //        $validator = Validator::make($request->all(), [
        //            'accountNumber' => ['required', 'exists:App\Models\Profile,account_number'],
        //        ]);

        //        $journal = Journal::query()->where('trx_ref', '=', $request->settlementId)->exists();
        //
        //        if ($journal) {
        //            return response()->json([
        //                'sessionId' => $request->sessionId,
        //                'requestSuccessful' => true,
        //                'responseMessage' => 'duplicate transaction',
        //                'responseCode' => '01',
        //            ], ResponseAlias::HTTP_OK);
        //        }
        //
        //        $profile = Profile::query()
        //            ->where('account_number', '=', $request->accountNumber)
        //            ->with('customer')
        //            ->firstOrFail();
        //
        //        $account = GetAccountInstance::getActiveInstance($profile);
        //
        //        $newJournalBalance = CreditJournalAction::execute($request, $account, 'webhook');
        //
        //        SendFireBaseNotificationQueue::dispatch($profile->customer->firebase_token ?? null, $this->notification);
        //
        //        AccountBalanceUpdateQueue::dispatch(
        //            $newJournalBalance->balance_before, $newJournalBalance->balance_after, $account);
        //        //TODO send email notification for when account is credited.
        //
        //        return response()->json([
        //            'requestSuccessful' => true,
        //            'sessionId' => $request->sessionId,
        //            'responseMessage' => 'success',
        //            'responseCode' => '00',
        //        ], ResponseAlias::HTTP_OK);


        //loop through the database table
        //look for any records that has not been processed
        //once a record has been processed please delete it so that the records cant be read
    }
}
