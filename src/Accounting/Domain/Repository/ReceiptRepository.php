<?php

namespace Src\Accounting\Domain\Repository;

use App\Models\Receipt;
use Illuminate\Support\Arr;
use Src\Accounting\Domain\Repository\Interfaces\InventoryRepositoryInterface;

class ReceiptRepository extends BaseRepository implements InventoryRepositoryInterface
{
    public function __construct(Receipt $model)
    {
        parent::__construct($model);
    }

    public function create(array $details)
    {
        Arr::set($details, 'customer_id', $this->customer);
        Arr::set($details, 'collaborator_id', $this->collaborator);

        return $this->model->query()->create($details);
    }
}
