<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $permissions)
    {
        $user = Auth::user();

        if ($user->is_super) {
            return $next($request);
        }

        $permissions = explode('|', $permissions);

//        if (!$user->hasAnyPermission($permissions)) {
//           return redirect()->back()->with('error', 'Không có quyền truy cập');
//        }

        return $next($request);
    }
}
