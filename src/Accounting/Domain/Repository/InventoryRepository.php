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

    public function getCountAll(array $details)
    {
        try {
            Arr::set($details, 'customer_id', $this->customer);

            if (! is_null($this->collaborator)) {
                Arr::set($details, 'collaborator_id', $this->collaborator);
            }

            return $this->model->query()->where($details)->count();
        } catch (\Exception $e) {
            logExceptionErrorMessage('BaseRepositoryCreate', $e);
        }
    }

    public function getSum(array $details)
    {
        try {
            Arr::set($details, 'customer_id', $this->customer);

            if (! is_null($this->collaborator)) {
                Arr::set($details, 'collaborator_id', $this->collaborator);
            }

            return $this->model->query()->where($details)->sum('selling_price');
        } catch (\Exception $e) {
            logExceptionErrorMessage('BaseRepositoryCreate', $e);
        }
    }
}
