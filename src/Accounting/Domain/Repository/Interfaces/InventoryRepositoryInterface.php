<?php

namespace Src\Accounting\Domain\Repository\Interfaces;

interface InventoryRepositoryInterface
{
    public function create(array $details);

    public function getCountAll(array $details);

    public function getSum(array $details);

    public function deleteByIds(array $ids);

    public function totalInventory(array $details);
}
