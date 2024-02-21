<?php

namespace Src\Wallets\Payments\Domain\Repository\Interfaces;

interface JournalRepositoryInterface
{
    public function getAllByParams(array $details);

    public function getAllByCreatedAtDate($start_date, $end_date, $details);
}
