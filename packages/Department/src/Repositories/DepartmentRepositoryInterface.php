<?php

namespace Packages\Department\Repositories;

use Packages\Department\Models\Department;

interface DepartmentRepositoryInterface
{
    public function index(array $input = [], array $with = [], $limit = 10);


    public function store(array $data);

    public function update(Department $model, array $data);

    public function delete(Department $model);

    public function getEmployee(Department $model);

    public function getNotEmployee(Department $model);

    public function syncEmployee(Department $model, array $data);
}
