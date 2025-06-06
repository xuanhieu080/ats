<?php

use Illuminate\Support\Facades\Route;
use Packages\Department\Http\Controllers\DepartmentController;

Route::group(['prefix' => 'departments', 'middleware' => ['web', 'auth']], function () {

    ////// Thêm nhân viên
    Route::post('{department}/employees', [DepartmentController::class, 'syncEmployee'])
        ->middleware('permission:department_update')
        ->name('departments.employees.post');

    Route::get('{department}/employees', [DepartmentController::class, 'employee'])
        ->middleware('permission:department_view')
        ->name('departments.employees');


    Route::get('/', [DepartmentController::class, 'index'])
        ->middleware('permission:department_view')
        ->name('departments.index');

    Route::get('create', [DepartmentController::class, 'create'])
        ->middleware('permission:department_add')
        ->name('departments.create');

    Route::get('{department}', [DepartmentController::class, 'show'])
        ->middleware('permission:department_view')
        ->name('departments.show');

    Route::post('/', [DepartmentController::class, 'store'])
        ->middleware('permission:department_add')
        ->name('departments.store');

    Route::post('{department}', [DepartmentController::class, 'update'])
        ->middleware('permission:department_update')
        ->name('departments.update');

    Route::delete('{department}', [DepartmentController::class, 'destroy'])
        ->middleware('permission:department_delete')
        ->name('departments.destroy');
});