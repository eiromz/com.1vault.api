<?php

namespace Src\Accounting\Domain\Repository\Interfaces;

interface InvoiceRepositoryInterface
{
    public function create(array $details);
    public function getClientDetails(array $details);
}
