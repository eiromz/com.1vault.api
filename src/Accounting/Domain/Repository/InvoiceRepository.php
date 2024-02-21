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

    public function getSalesAndDebtorList(array $details, $start_date, $end_date)
    {
        //use type = debtor
        //invoice has not been marked as paid
        // TODO: Implement getDebtorList() method.

        try {
            Arr::set($details, 'customer_id', $this->customer);

            if (! is_null($this->collaborator)) {
                Arr::set($details, 'collaborator_id', $this->collaborator);
            }

            return $this->model->query()->where($details)
                ->whereBetween('due_date', [$start_date, $end_date])
                ->get();
        } catch (\Exception $e) {
            logExceptionErrorMessage('InvoiceRepository=>getDebtorList', $e);
        }
    }
}
