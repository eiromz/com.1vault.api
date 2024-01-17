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
}
