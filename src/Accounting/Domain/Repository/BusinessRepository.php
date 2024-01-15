<?php

namespace Src\Accounting\Domain\Repository;

use App\Models\Business;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Src\Accounting\Domain\Repository\Interfaces\BusinessRepositoryInterface;

class BusinessRepository extends BaseRepository implements BusinessRepositoryInterface
{
    /**
     * UserRepository constructor.
     * @param Client $model
     */
    public function __construct(Business $model)
    {
        parent::__construct($model);
    }

    public function create(array $details)
    {
        Arr::set($details,'customer_id',$this->customer);
        Arr::set($details,'collaborator_id',$this->collaborator);

        return $this->model->query()->create($details);
    }
}
