<?php

namespace Packages\Department\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Helpers\CMS_ERROR;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Packages\Department\Http\Requests\CreateRequest;
use Packages\Department\Http\Requests\SyncEmployeeRequest;
use Packages\Department\Http\Requests\UpdateRequest;
use Packages\Department\Models\Department;
use Packages\Department\Services\DepartmentService;

class DepartmentController extends Controller
{
    protected DepartmentService $departmentService;

    public function __construct(DepartmentService $departmentService)
    {
        $this->departmentService = $departmentService;
    }

    public function index(Request $request)
    {
        $input = $request->all();
        $data = $this->departmentService->index($input);

        return view('Department::index', [
            'data'        => $data,
            'title'       => 'Phòng Ban|Dự Án',
        ]);
    }

    public function create(Request $request)
    {
        return view('Department::add', [
            'title'       => 'Thông Tin Phòng Ban|Dự Án',
            'breadcrumbs' => [
                route('departments.index') => 'Phòng Ban|Dự Án',
            ]
        ]);
    }

    public function store(CreateRequest $request): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $input = $request->validated();
            $this->departmentService->create($input);
            DB::commit();

            $this->departmentService->cache();
        } catch (Exception $exception) {
            DB::rollBack();
            CMS_ERROR::handle($exception);

            return redirect()->route('departments.index')->with('error', 'Có lỗi xảy ra');
        }

        return redirect()->route('departments.index')->with('success', 'Thêm dữ liệu thành công');
    }

    public function show(Department $department)
    {
        return view('Department::edit', [
            'title'       => 'Thông Tin Phòng Ban|Dự Án',
            'item'        => $department,
            'breadcrumbs' => [
                route('departments.index') => 'Phòng Ban|Dự Án',
            ]
        ]);
    }

    public function employee(Department $department)
    {
        $employees = $this->departmentService->getEmployee($department);
        $notEmployees = $this->departmentService->getNotEmployee($department);

        return view('Department::employee', [
            'title'         => 'Danh sách tài khoản',
            'item'         => $department,
            'employees'    => $employees,
            'notEmployees' => $notEmployees,
            'breadcrumbs' => [
                route('departments.index') => 'Phòng Ban|Dự Án',
            ]
        ]);
    }

    public function syncEmployee(SyncEmployeeRequest $request, Department $department)
    {
        try {
            DB::beginTransaction();
            $data = $request->validated();
            $this->departmentService->syncEmployee($department, $data);
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            CMS_ERROR::handle($exception);

            return response()->json(['message' => 'Đồng bộ tài khoản thất bại'], 400);
        }

        return response()->json(['message' => 'Đồng bộ tài khoản thành công'], 200);
    }

    public function update(UpdateRequest $request, Department $department): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $data = $request->validated();

            $item = $this->departmentService->update($department, $data);

            if (empty($item)) {
                throw new Exception('Cập nhật dữ liệu thất bại');
            }

            DB::commit();

            $this->departmentService->cache();
        } catch (Exception $exception) {
            DB::rollBack();
            CMS_ERROR::handle($exception);

            return redirect()->route('departments.show', $department->id)->with('error', 'Cập nhật dữ liệu thất bại');
        }

        return redirect()->route('departments.show', $item->id)->with('success', 'Cập nhật dữ liệu thành công');
    }

    public function destroy(Request $request, Department $department): JsonResponse
    {
        try {
            DB::beginTransaction();
            $this->departmentService->delete($department);
            DB::commit();

            $this->departmentService->cache();
        } catch (Exception $exception) {
            DB::rollBack();
            CMS_ERROR::handle($exception);

            return response()->json(['message' => 'Xoá dữ liệu thất bại'], 400);
        }

        return response()->json(['message' => 'Xoá dữ liệu thành công'], 200);
    }
}
