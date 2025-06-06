<?php

namespace Packages\Permission\Repositories;

use App\Models\User;

interface UserRepositoryInterface
{
    public function index(array $input = []);

    public function updatePermission(User $model, array $data);
}
