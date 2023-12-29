<?php

namespace Src\Wallets\Payments\Domain\Services;

use App\Models\Journal;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class ProvidusCreditService
{
    public function __construct(public $data)
    {}

    public function isDuplicate()
    {
        $journal = Journal::query()
            ->where('trx_ref','=',$this->data->settlementId)->exists();

        if($journal) {
            return jsonResponse(ResponseAlias::HTTP_OK,[
                "requestSuccessful"     => true,
                "sessionId"             => $this->data->sessionId,
                "responseMessage"       => "duplicate transaction",
                "responseCode"          => "01"
            ]);
        }
    }
}
