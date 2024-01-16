<?php

namespace Src\Accounting\Domain\Repository;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Src\Accounting\Domain\Repository\Interfaces\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class BaseRepository implements BaseRepositoryInterface
{
    /**
     * @var Model
     */
    protected Model $model;
    protected $customer = null;
    protected $collaborator = null;
    /**
     * BaseRepository constructor.
     *
     * @param Model $model
     * @throws \Exception
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }
    public function setUser($user): void
    {
        $customer = Customer::query()
            ->where('ACCOUNTID','=',$user->ACCOUNTID)
            ->whereIsOwner(1)
            ->firstOrFail();

        $this->customer = $customer->id;
        $this->owner($user);
        $this->member($user);
    }
    public function owner($user): void
    {
        if($user->is_owner) {
            $this->customer = $user->id;
        }
    }
    public function member($user): void
    {
        if($user->is_member) {
            $this->collaborator = $user->id;
        }
    }
    public function getAll()
    {
        return $this->model->query()->all();
    }
    public function getById($id) : Model
    {
        return $this->model->query()->findOrFail($id);
    }
    public function delete($id)
    {
        return $this->model->query()->delete($id);
    }
    public function create(array $details)
    {
        Arr::set($details,'customer_id',$this->customer);

        if(!is_null($this->collaborator)) {
            Arr::set($details,'collaborator_id',$this->collaborator);
        }

        return $this->model->query()->create($details);
    }
    public function update($id, array $newDetails) :Model
    {
        return $this->model->query()->whereId($id)->update($newDetails);
    }
    public function getDetailsByParams(array $details): Model|Builder|null
    {
        Arr::set($details,'customer_id',$this->customer);

        if(!is_null($this->collaborator)) {
            Arr::set($details,'collaborator_id',$this->collaborator);
        }

        return $this->model->query()->where($details)->first();
    }
    public function getAllByParams(array $details): Collection|array
    {
        Arr::set($details,'customer_id',$this->customer);

        if(!is_null($this->collaborator)) {
            Arr::set($details,'collaborator_id',$this->collaborator);
        }

        return $this->model->query()->where($details)->get();
    }
}
