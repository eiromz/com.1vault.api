<?php

namespace Src\Accounting\Domain\Repository;

use App\Models\Invoice;
use Illuminate\Support\Arr;
use Src\Accounting\Domain\Repository\Interfaces\InvoiceRepositoryInterface;

class InvoiceRepository extends BaseRepository implements InvoiceRepositoryInterface
{
    public function __construct(Invoice $model)
    {
        parent::__construct($model);
    }

    public function create(array $details)
    {
        Arr::set($details, 'customer_id', $this->customer);
        Arr::set($details, 'collaborator_id', $this->collaborator);

        return $this->model->query()->create($details);
    }

    public function getClientDetails(array $details)
    {
        Arr::set($details, 'customer_id', $this->customer);
        if (! is_null($this->collaborator)) {
            Arr::set($details, 'collaborator_id', $this->collaborator);
        }

        return $this->model->query()->where($details)->first();
    }
}
