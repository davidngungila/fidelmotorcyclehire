<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (! auth()->check()) {
            return redirect('/login');
        }

        $user = $request->user();
        $roleList = is_array($roles[0]) ? $roles[0] : $roles;

        if (! $user->hasRole($roleList)) {
            abort(403);
        }

        return $next($request);
    }
}
