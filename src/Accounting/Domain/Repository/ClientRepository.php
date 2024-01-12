<?php

namespace Src\Accounting\Domain\Repository;

use App\Models\Client;
use Src\Accounting\Domain\Repository\Interfaces\ClientRepositoryInterface;

class ClientRepository extends BaseRepository implements ClientRepositoryInterface
{
    /**
     * UserRepository constructor.
     * @param Client $model
     */
    public function __construct(Client $model)
    {
        parent::__construct($model);
    }
}
