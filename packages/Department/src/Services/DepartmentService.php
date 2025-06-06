<?php

namespace Packages\Department\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Packages\Department\Repositories\DepartmentRepository;
use Packages\Department\Models\Department;

class DepartmentService
{
    protected DepartmentRepository $departmentRepository;

    public function __construct(DepartmentRepository $departmentRepository)
    {
        $this->departmentRepository = $departmentRepository;
    }

    public function index(array $data)
    {
        $with = [];
        $limit = Arr::get($data, 'limit', 10);
        return $this->departmentRepository->index($data, $with, $limit);
    }

    public function create(array $data): bool|Department
    {

        $data['is_active'] = (bool)($data['is_active'] ?? false);
        $data['created_by'] = Auth::id();
        $data['updated_by'] = Auth::id();

        return $this->departmentRepository->store($data);
    }

    public function update(Department $department, array $data): Model|bool
    {
        $data['is_active'] = (bool)($data['is_active'] ?? false);
        $data['updated_by'] = Auth::id();

        return $this->departmentRepository->update($department, $data);
    }

    public function delete(Department $department): bool
    {
        return $this->departmentRepository->delete($department);
    }

    public function getEmployee(Department $department): Collection|array
    {
        return $this->departmentRepository->getEmployee($department);
    }

    public function getNotEmployee(Department $department): Collection|array
    {
        return $this->departmentRepository->getNotEmployee($department);
    }

    public function syncEmployee(Department $department, $data): void
    {
        $this->departmentRepository->syncEmployee($department, $data);
    }

    public function cache(): void
    {
        $this->departmentRepository->cache();
    }
}