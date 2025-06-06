<?php

namespace Packages\Permission\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Packages\Permission\Models\Role;
use Packages\Permission\Repositories\RoleRepository;

class RoleService
{ 
    protected RoleRepository $roleRepository;

    public function __construct(RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    public function index(array $data)
    {
        $with = [];
        $limit = Arr::get($data, 'limit', 10);
        return $this->roleRepository->index($data, $with, $limit);
    }
    public function create(array $data) :bool|Role
    {
        return $this->roleRepository->store($data);
    }

    public function update(Role $role, array $data): Model|bool
    {
        return $this->roleRepository->update($role, $data);
    }


    public function updatePermission(Role $role, array $data): Model|bool
    {
        $ids = Arr::get($data, 'ids', []);
        return $this->roleRepository->updatePermission($role, $ids);
    }

    public function delete(Role $role): bool
    {
        return $this->roleRepository->delete($role);
    }
}