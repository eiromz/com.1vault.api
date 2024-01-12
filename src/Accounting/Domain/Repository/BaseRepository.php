<?php

namespace Src\Accounting\Domain\Repository;

use App\Models\Client;
use App\Models\Customer;
use Src\Accounting\Domain\Repository\Interfaces\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

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
     */
    public function __construct(Model $model)
    {
        if(auth()->user()->is_owner){
            $this->customer = auth()->user()->id;
        }

        if(auth()->user()->is_member){
            $this->collaborator = auth()->user()->id;
            $this->customer = Customer::query()
                ->where('ACCOUNTID','=',auth()->user()->ACCOUNTID)
                ->whereIsOwner(1)
                ->first();
        }

        $this->model = $model;
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
}
