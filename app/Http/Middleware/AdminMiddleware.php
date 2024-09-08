<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle($request, Closure $next)
    {
        // التحقق من أن المستخدم هو إداري (admin)
        if (Auth::check() && Auth::user()->role == 'admin') {
            return $next($request); // السماح بتمرير الطلب
        }

        // في حال لم يكن المستخدم إداري، إرجاع خطأ 403
        return response()->json(['message' => 'Unauthorized'], 403);
    }
}
