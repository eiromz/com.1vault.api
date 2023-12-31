<?php

namespace Src\Wallets\Payments\Domain\Services;

class CreditEntryJournalService
{
    //fetch the wallet service
    //construct this with the account object to get the bal_before_after

    public function __construct(public $data)
    {}

    public function isDuplicate()
    {}
}
