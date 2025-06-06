<?php

namespace Packages\Permission\Repositories;

interface PermissionRepositoryInterface
{
    public function index(array $input = [], array $with = []);
    public function getParent(array $input = [], array $with = []);
}
