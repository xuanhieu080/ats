<?php

namespace Packages\Permission\Http\Controllers;

use App\Helpers\Support;
use App\Http\Controllers\Controller;
use App\Helpers\CMS_ERROR;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Packages\Permission\Http\Requests\Roles\CreateRequest;
use Packages\Permission\Http\Requests\Roles\UpdatePermissionRequest;
use Packages\Permission\Http\Requests\Roles\UpdateRequest;
use Packages\Permission\Models\Role;
use Packages\Permission\Services\PermissionService;
use Packages\Permission\Services\RoleService;
use Throwable;

class RoleController extends Controller
{
    protected RoleService $roleService;
    protected PermissionService $permissionService;

    public function __construct(RoleService $roleService, PermissionService $permissionService)
    {
        $this->roleService = $roleService;
        $this->permissionService = $permissionService;
    }

    public function index(Request $request)
    {
        $input = $request->all();
        $data = $this->roleService->index($input);

//        return view('Permission::roles.index', [
//            'title' => 'Vai Trò',
//            'data'  => $data
//        ]);
    }

    /**
     * @throws Throwable
     */
    public function store(CreateRequest $request): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $input = $request->validated();
            $this->roleService->create($input);
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            CMS_ERROR::handle($exception);

            return redirect()->back()->with('error', 'Có lỗi xảy ra');
        }

        return redirect()->back()->with('success', 'Thêm dữ liệu thành công');
    }

    public function show(Role $role)
    {
        if (in_array($role->id, Support::disableRoleIds())) {
            return redirect()->route('roles.index')->with('error', 'Không được phép cập nhật vai trò mặc định');
        }

        return view('Permission::roles.edit', [
            'title'       => 'Thông Tin Vai Trò',
            'item'        => $role,
            'breadcrumbs' => [route('roles.index') => 'Vai Trò']
        ]);
    }

    public function permission(Role $role)
    {
        $permissions = $this->permissionService->index([]);
        $permissionArray = $role->permissions()->get()->pluck('id')->toArray();
        return view('Permission::roles.permission', [
            'title'           => 'Thông Tin Vai Trò',
            'item'            => $role,
            'permissions'     => $permissions,
            'permissionArray' => $permissionArray,
            'breadcrumbs'     => [route('roles.index') => 'Vai Trò']
        ]);
    }

    public function update(UpdateRequest $request, Role $role): RedirectResponse
    {
        if (in_array($role->id, Support::disableRoleIds())) {
            return redirect()->route('roles.index')->with('error', 'Không được phép cập nhật vai trò mặc định');
        }

        try {
            DB::beginTransaction();
            $data = $request->validated();

            $item = $this->roleService->update($role, $data);

            if (empty($item)) {
                throw new Exception('Cập nhật dữ liệu thất bại');
            }

            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            CMS_ERROR::handle($exception);

            return redirect()->route('roles.show', $role->id)->with('error', 'Cập nhật dữ liệu thất bại');
        }

        return redirect()->route('roles.show', $item->id)->with('success', 'Cập nhật dữ liệu thành công');
    }

    public function updatePermission(UpdatePermissionRequest $request, Role $role): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $data = $request->validated();

            $item = $this->roleService->updatePermission($role, $data);

            if (empty($item)) {
                throw new Exception('Cập nhật dữ liệu thất bại');
            }

            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            CMS_ERROR::handle($exception);

            return redirect()->route('roles.permission', $role->id)->with('error', 'Cập nhật dữ liệu thất bại');
        }

        return redirect()->route('roles.permission', $item->id)->with('success', 'Cập nhật dữ liệu thành công');
    }

    public function destroy(Request $request, Role $role): JsonResponse
    {
        if (in_array($role->id, Support::disableRoleIds())) {
            return response()->json(['message' => 'Không được phép xóa vai trò mặc định'], 403);
        }

        try {
            DB::beginTransaction();
            $this->roleService->delete($role);
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            CMS_ERROR::handle($exception);

            return response()->json(['message' => 'Xoá dữ liệu thất bại'], 400);
        }

        return response()->json(['message' => 'Xoá dữ liệu thành công'], 200);
    }
}
