<?php

use Illuminate\Support\Facades\Route;
use Packages\Permission\Http\Controllers\RoleController;
use Packages\Permission\Http\Controllers\PermissionController;
use Packages\Permission\Http\Controllers\UserController;

Route::group(['prefix' => 'permissions', 'middleware' => ['web', 'auth', 'super-admin']], function () {
    Route::get('users', [UserController::class, 'permission'])->name('permissions.users.index');
    Route::post('users/{user}', [UserController::class, 'updatePermission'])->name('permissions.users.update');

    Route::get('/', [PermissionController::class, 'index'])->name('permissions.index');
    Route::get('{permission}', [PermissionController::class, 'show'])->name('permissions.show');
    Route::post('/', [PermissionController::class, 'store'])->name('permissions.store');
    Route::post('{permission}', [PermissionController::class, 'update'])->name('permissions.update');
    Route::delete('{permission}', [PermissionController::class, 'destroy'])->name('permissions.destroy');
});

Route::group(['prefix' => 'roles', 'middleware' => ['web', 'auth', 'super-admin']], function () {
    Route::get('/', [RoleController::class, 'index'])->name('roles.index');
    Route::get('{role}/permissions', [RoleController::class, 'permission'])->name('roles.permission');
    Route::post('{role}/permissions', [RoleController::class, 'updatePermission'])->name('roles.permission.update');
    Route::get('{role}', [RoleController::class, 'show'])->name('roles.show');
    Route::post('/', [RoleController::class, 'store'])->name('roles.store');
    Route::post('{role}', [RoleController::class, 'update'])->name('roles.update');
    Route::delete('{role}', [RoleController::class, 'destroy'])->name('roles.destroy');
});
