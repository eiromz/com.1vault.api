<?php

namespace Src\Accounting\Domain\Repository;

use App\Models\Business;
use Src\Accounting\Domain\Repository\Interfaces\ClientRepositoryInterface;

class BusinessRepository extends BaseRepository implements ClientRepositoryInterface
{
    /**
     * UserRepository constructor.
     * @param Client $model
     */
    public function __construct(Business $model)
    {
        parent::__construct($model);
    }
}
