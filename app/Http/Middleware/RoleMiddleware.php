<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Gate;

class RoleMiddleware
{
    public function handle($request, Closure $next, $role)
    {
        if (!Gate::allows('hasRole', $role)) {
            abort(403, 'No tienes permiso para acceder a esta página.');
        }

        return $next($request);
    }
}
