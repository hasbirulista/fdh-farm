<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // pastikan sudah login
        if (!Auth::check()) {
            abort(403, 'Unauthorized');
        }

        $user = Auth::user();

        // owner bebas akses
        if ($user->role === 'owner') {
            return $next($request);
        }

        // cek role di parameter middleware
        if (!in_array($user->role, $roles)) {
            abort(403, 'Akses ditolak');
        }

        return $next($request);
    }
}
