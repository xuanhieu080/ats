<?php

namespace Packages\Department\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Packages\Department\Models\Department;


class DepartmentRepository implements DepartmentRepositoryInterface
{
    public Department $model;

    public function __construct(Department $model)
    {
        $this->model = $model;
    }

    public function index(array $input = [], array $with = [], $limit = 10): mixed
    {
        return $this->model->with($with)
            ->when(!empty($input['search']), function ($query) use ($input) {
                $query->where('name', 'like', '%' . $input['search'] . '%');
            })
            ->orderBy('name')
            ->paginate($limit);
    }

    public function store(array $data): bool|Model
    {
        $model = $this->model->fill($data);

        if ($model->save()) {

            return $model;
        }

        return false;
    }

    public function update(Department $model, array $data): bool|Model
    {
        $model->fill($data);

        if ($model->save()) {
            return $model;
        }

        return false;
    }

    public function delete(Department $model): bool
    {
        return $model->delete();
    }

    public function getEmployee(Department $model): Collection|array
    {
        return User::query()
            ->select('id', 'name')
            ->where('department_id', $model->id)
            ->get();
    }

    public function getNotEmployee(Department $model): Collection|array
    {
        return User::query()
            ->select('id', 'name')
            ->where(function ($query) use ($model) {
                $query->where('department_id', '<>', $model->id)
                    ->orWhereNull('department_id');
            })
            ->get();
    }

    public function syncEmployee(Department $model, array $data): void
    {
        $ids = Arr::get($data, 'ids');

        User::query()
            ->where('department_id', $model->id)
            ->update([
                'department_id' => null
            ]);

        User::query()
            ->whereIn('id', $ids)
            ->update([
                'department_id' => $model->id
            ]);
    }

    public function cache(): void
    {
        $cacheKey = 'departments-cache';
        $seconds = 365 * 24 * 60 * 60;

        $departments = $this->model::query()
            ->select('id', 'name', 'slug')
            ->where('is_active', 1)
            ->orderBy('name', 'asc')
            ->get();

        Cache::put($cacheKey, $departments, $seconds);
    }
}
