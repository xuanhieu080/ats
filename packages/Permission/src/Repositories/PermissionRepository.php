<?php

namespace Packages\Permission\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Packages\Permission\Models\Permission;

class PermissionRepository implements PermissionRepositoryInterface
{

    public Permission $model;

    public function __construct(Permission $model)
    {
        $this->model = $model;
    }

    public function index(array $input = [], array $with = []): mixed
    {
        return $this->model->with($with)->get();
    }

    public function getParent(array $input = [], array $with = []): array|Collection
    {
        return $this->model->with($with)
            ->when(isset($input['name']), function ($q) use ($input) {
                return $q->where('name', 'like', '%' . $input['name'] . '%')
                    ->orWhere('title', 'like', '%' . $input['name'] . '%');
            })->whereNull('parent_id')->get();
    }
}
