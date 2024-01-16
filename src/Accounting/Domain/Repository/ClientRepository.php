<?php

namespace Src\Accounting\Domain\Repository;

use App\Models\Client;
use Exception;
use Illuminate\Support\Arr;
use Src\Accounting\Domain\Repository\Interfaces\ClientRepositoryInterface;

class ClientRepository extends BaseRepository implements ClientRepositoryInterface
{
    /**
     * UserRepository constructor.
     * @param Client $model
     * @throws Exception
     */
    public function __construct(Client $model)
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
