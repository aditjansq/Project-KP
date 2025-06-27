<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Ambil user yang sedang login
        $user = Auth::user();

        // Jika user tidak terautentikasi, tampilkan 403
        if (!$user) {
            abort(403, 'Unauthorized');
        }

        // Normalisasi role untuk dibandingkan
        $userRole = strtolower($user->job);
        $allowedRoles = array_map('strtolower', $roles);

        // Cek jika role pengguna tidak ada dalam daftar role yang diizinkan
        if (!in_array($userRole, $allowedRoles)) {
            abort(403, 'Akses ditolak.');  // Akses ditolak, Laravel akan merender halaman 403
        }

        return $next($request);
    }
}
