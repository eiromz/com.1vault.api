<?php

namespace Src\Accounting\Domain\Repository;

use App\Models\Client;
use Exception;
use Src\Accounting\Domain\Repository\Interfaces\ClientRepositoryInterface;

class ClientRepository extends BaseRepository implements ClientRepositoryInterface
{
    /**
     * UserRepository constructor.
     *
     * @throws Exception
     */
    public function __construct(Client $model)
    {
        parent::__construct($model);
    }
}
