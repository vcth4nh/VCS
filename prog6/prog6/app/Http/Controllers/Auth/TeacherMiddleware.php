<?php

namespace App\Http\Controllers\Auth;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class TeacherMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::user()->role != TEACHER)
            return abort(401);

        return $next($request);
    }
}
