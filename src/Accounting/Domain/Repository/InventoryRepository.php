<?php

namespace Src\Accounting\Domain\Repository;

use App\Models\Inventory;
use Exception;
use Illuminate\Support\Arr;
use Src\Accounting\Domain\Repository\Interfaces\InventoryRepositoryInterface;

class InventoryRepository extends BaseRepository implements InventoryRepositoryInterface
{
    /**
     * @throws Exception
     */
    public function __construct(Inventory $model)
    {
        parent::__construct($model);
    }

}
