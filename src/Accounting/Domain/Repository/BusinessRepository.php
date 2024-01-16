<?php

namespace Src\Accounting\Domain\Repository;

use App\Models\Business;
use App\Models\Customer;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Src\Accounting\Domain\Repository\Interfaces\BusinessRepositoryInterface;

class BusinessRepository extends BaseRepository implements BusinessRepositoryInterface
{
    /**
     * UserRepository constructor.
     * @param Business $model
     * @throws Exception
     */
    public function __construct(Business $model)
    {
        parent::__construct($model);
    }
}
