<?php

namespace Src\Wallets\Payments\Domain\Repository;

use App\Models\Journal;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Src\Accounting\Domain\Repository\BaseRepository;
use Src\Wallets\Payments\Domain\Repository\Interfaces\JournalRepositoryInterface;

class JournalRepository extends BaseRepository implements JournalRepositoryInterface
{
    public function __construct(Journal $model)
    {
        parent::__construct($model);
    }

    public function getAllByParams(array $details): Collection|array
    {
        try {
            Arr::set($details, 'customer_id', $this->customer);

            return $this->model->query()->where($details)->get();
        } catch (\Exception $e) {
            logExceptionErrorMessage('BaseRepositoryCreate', $e);
        }
    }

    public function getAllByCreatedAtDate($start_date,$end_date,$details)
    {
        try {
            Arr::set($details, 'customer_id', $this->customer);

            return $this->model->query()->where($details)
                ->whereBetween('created_at',[$start_date,$end_date])
                ->get();
        } catch (\Exception $e) {
            logExceptionErrorMessage('BaseRepositoryCreate', $e);
        }
    }
}
