<?php

namespace Packages\Permission\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Packages\Permission\Repositories\UserRepository;

class UserService
{ 
    protected UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function index(array $data): Collection
    {
        return $this->userRepository->index($data);
    }

    public function updatePermission(User $user, array $data): Model|bool
    {
        $ids = Arr::get($data, 'ids', []);
        return $this->userRepository->updatePermission($user, $ids);
    }
}