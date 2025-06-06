<?php

namespace Packages\Permission\Repositories;

use Packages\Permission\Models\Role;

interface RoleRepositoryInterface
{
    public function index(array $input = [], array $with = [], $limit = null);

    public function store(array $data);

    public function update(Role $model, array $data);

    public function delete(Role $model);

    public function updatePermission(Role $model, array $data);
}
