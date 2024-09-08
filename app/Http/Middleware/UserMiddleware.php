<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class UserMiddleware
{
    public function handle($request, Closure $next)
    {
        // التحقق مما إذا كان المستخدم عاديًا وليس إداريًا
        if (Auth::check() && Auth::user()->role == 'user') {
            return $next($request); // السماح بتمرير الطلب
        }

        // إذا لم يكن المستخدم عاديًا أو لم يتم تسجيل الدخول
        return response()->json(['message' => 'Unauthorized'], 403);
    }
}
