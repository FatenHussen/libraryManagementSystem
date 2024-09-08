<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle($request, Closure $next, ...$roles)
    {
        $user = Auth::user();

        if (!$user || !in_array($user->role, $roles)) {
            // If the user does not have the required role, deny access
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        return $next($request);
    }
}
