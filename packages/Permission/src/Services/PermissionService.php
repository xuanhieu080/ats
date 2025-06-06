<?php

namespace Packages\Permission\Services;

use Illuminate\Database\Eloquent\Collection;
use Packages\Permission\Repositories\PermissionRepository;

class PermissionService
{
    protected PermissionRepository $permissionRepository;

    public function __construct(PermissionRepository $permissionRepository)
    {
        $this->permissionRepository = $permissionRepository;
    }

    public function index(array $data, array $with = [])
    {
        return $this->permissionRepository->index($data, $with);
    }

    public function getParent(array $data, array $with = []): array|Collection
    {
        return $this->permissionRepository->getParent($data, $with);
    }
}
