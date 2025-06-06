<?php

namespace Packages\Permission\Repositories;

use Illuminate\Database\Eloquent\Model;
use Packages\Permission\Models\Role;
use Packages\Permission\Repositories\RoleRepositoryInterface;


class RoleRepository implements RoleRepositoryInterface
{
    public Role $model;

    public function __construct(Role $model)
    {
        $this->model = $model;
    }
    public function index(array $input = [], array $with = [], $limit = 10): mixed
    {
        return $this->model->with($with)
            ->when(isset($input['name']), function ($q) use ($input) {
                return $q->where('name', 'like', '%' . $input['name'] . '%')
                    ->orWhere('title', 'like', '%' . $input['name'] . '%');
            })->paginate($limit);
    }

    public function store(array $data): bool|Model
    {
        $model = $this->model->fill($data);

        if ($model->save()) {

            return $model;
        }

        return false;
    }

    public function update(Role $model, array $data): bool|Model
    {
        $model->fill($data);

        if ($model->save()) {
            return $model;
        }

        return false;
    }

    public function updatePermission(Role $model, array $data): bool|Model
    {
        $model->syncPermissions($data);

        return $model;
    }

    public function delete(Role $model): bool
    {
        return $model->delete();
    }
}
