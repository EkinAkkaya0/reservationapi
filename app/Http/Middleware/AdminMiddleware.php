<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Kullanıcının 'admin' rolüne sahip olup olmadığını kontrol et
        if (Auth::check() && Auth::user()->role === 'admin') {
            return $next($request);
        }

        return response()->json(['message' => 'Unauthorized.'], 403);
    }
}
