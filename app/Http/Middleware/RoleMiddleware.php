<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;


class RoleMiddleware
{
    public function handle($request, Closure $next, ...$roles)
    {
        $user = Auth::user();
        if (!$user) {
            abort(403, 'Unauthorized');
        }

        // Normalisasi role untuk dibandingkan
        $userRole = strtolower($user->job);
        $allowedRoles = array_map('strtolower', $roles);

        if (!in_array($userRole, $allowedRoles)) {
            abort(403, 'Akses ditolak.');
        }

        return $next($request);
    }
}