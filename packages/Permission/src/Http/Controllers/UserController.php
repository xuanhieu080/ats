<?php

namespace Packages\Permission\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Helpers\CMS_ERROR;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Packages\Permission\Http\Requests\Permissions\UpdatePermissionToUserRequest;
use Packages\Permission\Services\PermissionService;
use Packages\Permission\Services\UserService;

class UserController extends Controller
{
    protected UserService $userService;
    protected PermissionService $permissionService;

    public function __construct(UserService $userService, PermissionService $permissionService)
    {
        $this->userService = $userService;
        $this->permissionService = $permissionService;
    }

    public function permission(Request $request)
    {

        $permissionArray = [];

        $users = $this->userService->index([]);
        $permissions = [];

        if (!empty($request->input('user_id'))) {
            $user = User::query()->find($request->input('user_id'));
            if (!empty($user)) {
                $permissionArray = $user->getAllPermissions()->pluck('id')->toArray();
            }
            $permissions = $this->permissionService->index([]);
        }

        return view('Permission::permissions.user', [
            'title'           => 'Quản Lý Quyền Của Người Dùng',
            'users'           => $users,
            'permissions'     => $permissions,
            'permissionArray' => $permissionArray
        ]);
    }

    public function updatePermission(UpdatePermissionToUserRequest $request, User $user): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $data = $request->validated();

            $item = $this->userService->updatePermission($user, $data);

            if (empty($item)) {
                throw new \Exception('Cập nhật dữ liệu thất bại');
            }

            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            CMS_ERROR::handle($exception);

            return redirect()->route('permissions.users.index', ['user_id' => $user->id])->with('error', 'Cập nhật dữ liệu thất bại');
        }

        return redirect()->route('permissions.users.index', ['user_id' => $user->id])->with('success', 'Cập nhật dữ liệu thành công');
    }
}
