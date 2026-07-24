<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PermissionMiddleware
{
    public function handle(Request $request, Closure $next, ...$permissions): Response
    {
        if (! auth()->check()) {
            return redirect('/login');
        }

        $user = $request->user();
        $permissionList = is_array($permissions[0]) ? $permissions[0] : $permissions;

        $hasPermission = $user->roles->pluck('permissions')->flatten()->pluck('name')->intersect($permissionList)->isNotEmpty();

        if (! $hasPermission) {
            abort(403);
        }

        return $next($request);
    }
}
