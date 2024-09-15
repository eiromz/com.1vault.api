<?php

namespace Src\Merchant\Domain\Services;

class ProcessProvidusWebhookData
{
    public function __construct(private array $request) {}

    protected function save()
    {
        if (! is_array($this->request)) {
            return;
        }

        //create the webhook data store in the database
    }

    protected function modifyRequestData()
    {
        return [
            'session_id' => $this->request['sessionId'],
            'settlement_id' => $this->request['settlementId'],
            'payload' => json_encode($this->request),
            'processed' => true,
        ];
    }
}
