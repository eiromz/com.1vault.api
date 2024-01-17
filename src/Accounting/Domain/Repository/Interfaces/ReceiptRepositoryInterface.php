<?php

namespace Src\Accounting\Domain\Repository\Interfaces;

interface ReceiptRepositoryInterface
{
    public function create(array $details);
}
