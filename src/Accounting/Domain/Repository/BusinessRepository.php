<?php

namespace Src\Accounting\Domain\Repository;

use App\Models\Business;
use Exception;
use Src\Accounting\Domain\Repository\Interfaces\BusinessRepositoryInterface;

class BusinessRepository extends BaseRepository implements BusinessRepositoryInterface
{
    /**
     * UserRepository constructor.
     *
     * @throws Exception
     */
    public function __construct(Business $model)
    {
        parent::__construct($model);
    }
}
