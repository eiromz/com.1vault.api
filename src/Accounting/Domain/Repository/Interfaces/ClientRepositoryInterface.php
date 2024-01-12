<?php

namespace Src\Accounting\Domain\Repository\Interfaces;

interface ClientRepositoryInterface
{
    public function create(array $clientDetails);
}
