<?php

namespace Src\Accounting\Domain\Repository\Interfaces;

interface InvoiceRepositoryInterface
{
    public function getSalesAndDebtorList(array $details, $start_date, $end_date);
}
