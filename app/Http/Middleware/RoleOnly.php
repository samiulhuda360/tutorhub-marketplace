<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleOnly
{
    public function handle(Request $request, Closure $next, string $role)
    {
        if (! auth()->check()) {
            return redirect()->route($role === 'admin' ? 'admin.login' : 'login');
        }

        $user = auth()->user();
        $allowed = $role === 'admin' ? $user->isAdmin() : $user->role === $role;
        if (! $allowed) {
            abort(403, 'Unauthorized.');
        }

        return $next($request);
    }
}
