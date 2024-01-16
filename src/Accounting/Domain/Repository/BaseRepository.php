<?php

namespace Src\Accounting\Domain\Repository;

use App\Models\Client;
use App\Models\Customer;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Arr;
use Src\Accounting\Domain\Repository\Interfaces\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpFoundation\Response;

class BaseRepository implements BaseRepositoryInterface
{
    /**
     * @var Model
     */
    protected Model $model;
    protected $collaborator = null,$customer = null;

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
        if($user && $user->is_owner) {
            $this->customer = $user->id;
        }

        if($user && $user->is_member) {
            $this->collaborator = $user->id;
            $customer = Customer::query()->where('ACCOUNTID','=',$user->ACCOUNTID)
                ->whereIsOwner(1)
                ->first();
            $this->customer = $customer->id;
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
        $this->model->query()->delete($id);
    }
    public function create(array $details)
    {
        return $this->model->query()->create($details);
    }
    public function update($id, array $newDetails) :Model
    {
        return $this->model->query()->whereId($id)->update($newDetails);
    }
    public function getDetailsByParams(array $details)
    {
        Arr::set($details,'customer_id',$this->customer);

        if(!is_null($this->collaborator)) {
            Arr::set($details,'collaborator_id',$this->collaborator);
        }

        return $this->model->query()->where($details)->first();
    }

}
