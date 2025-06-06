<?php

namespace Packages\Permission\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Packages\Permission\Http\Resources\PermissionParentResource;
use Packages\Permission\Services\PermissionService;

class PermissionController extends Controller
{
    protected PermissionService $permissionService;

    public function __construct(PermissionService $permissionService)
    {
        $this->permissionService = $permissionService;
    }

    public function index(Request $request)
    {
        $data = $this->permissionService->getParent([], ['children']);

       return PermissionParentResource::collection($data);
    }
}
