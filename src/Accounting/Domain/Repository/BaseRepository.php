<?php

namespace Src\Accounting\Domain\Repository;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Src\Accounting\Domain\Repository\Interfaces\BaseRepositoryInterface;

class BaseRepository implements BaseRepositoryInterface
{
    protected Model $model;

    protected $customer = null;

    protected $collaborator = null;

    /**
     * BaseRepository constructor.
     *
     * @throws \Exception
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function setUser($user): void
    {
        $customer = Customer::query()
            ->where('ACCOUNTID', '=', $user->ACCOUNTID)
            ->where('is_owner','=',1)
            ->firstOrFail();

        $this->customer = $customer->id;
        $this->owner($user);
        $this->member($user);
    }

    public function owner($user): void
    {
        if ($user->is_owner) {
            $this->customer = $user->id;
        }
    }

    public function member($user): void
    {
        if ($user->is_member) {
            $this->collaborator = $user->id;
        }
    }

    public function getAll()
    {
        try {
            return $this->model->query()->all();
        } catch (\Exception $e) {
            logExceptionErrorMessage('BaseRepositoryCreate', $e);
        }
    }

    public function getById($id): Model
    {
        try {
            return $this->model->query()->findOrFail($id);
        } catch (\Exception $e) {
            logExceptionErrorMessage('BaseRepositoryCreate', $e);
        }
    }

    public function delete($id)
    {
        try {
            return $this->model->query()->delete($id);
        } catch (\Exception $e) {
            logExceptionErrorMessage('BaseRepositoryCreate', $e, [], 'critical');
        }
    }

    public function create(array $details)
    {
        try {
            Arr::set($details, 'customer_id', $this->customer);

            if (! is_null($this->collaborator)) {
                Arr::set($details, 'collaborator_id', $this->collaborator);
            }

            return $this->model->query()->create($details);
        } catch (\Exception $e) {
            logExceptionErrorMessage('BaseRepositoryCreate', $e);
        }
    }

    public function update($id, array $newDetails)
    {
        try {
            return $this->model->query()->whereId($id)->update($newDetails);
        } catch (\Exception $e) {
            logExceptionErrorMessage('BaseRepositoryCreate', $e);
        }
    }

    public function getDetailsByParams(array $details)
    {
        try {
            Arr::set($details, 'customer_id', $this->customer);

            if (! is_null($this->collaborator)) {
                Arr::set($details, 'collaborator_id', $this->collaborator);
            }

            return $this->model->query()->where($details)->first();
        } catch (\Exception $e) {
            logExceptionErrorMessage('BaseRepositoryCreate', $e);
        }
    }

    public function getAllByParams(array $details): Collection|array
    {
        try {
            Arr::set($details, 'customer_id', $this->customer);

            if (! is_null($this->collaborator)) {
                Arr::set($details, 'collaborator_id', $this->collaborator);
            }

            return $this->model->query()->where($details)->latest()->get();
        } catch (\Exception $e) {
            logExceptionErrorMessage('BaseRepositoryCreate', $e);
        }
    }
}
