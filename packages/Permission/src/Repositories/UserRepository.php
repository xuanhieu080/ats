<?php

namespace Packages\Permission\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;


class UserRepository implements UserRepositoryInterface
{
    public User $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    /**
     * @param array $input
     * @return Collection
     */
    public function index(array $input = []):Collection
    {
        return $this->model->with([])
            ->when(isset($input['name']), function ($q) use ($input) {
                return $q->where('name', 'like', '%' . $input['name'] . '%')
                    ->orWhere('email', 'like', '%' . $input['name'] . '%');
            })->select('id', 'name')
            ->get();
    }

    public function updatePermission(User $user, array $data): bool|Model
    {
        $user->syncPermissions($data);

        return $user;
    }
}
