<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureProfileCompleted
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // 1. Pastikan user login
        // 2. Jika profil BELUM lengkap
        // 3. DAN user tidak sedang mengakses rute update profil atau logout (supaya tidak loop/terkunci selamanya)
        if ($user && !$user->is_profile_complete) {

            // Izinkan akses ke rute dashboard (tempat modal muncul), update profile, dan logout
            $allowedRoutes = [
                'user.index',      // Dashboard User
                'admin.index',     // Dashboard Admin
                'operator.index',  // Dashboard Operator
                'profile.update',  // Rute post form update
                'logout',          // Rute logout
            ];

            if (!in_array($request->route()->getName(), $allowedRoutes)) {

                // Redirect sesuai role
                $route = match ($user->role) {
                    'admin' => 'admin.index',
                    'operator' => 'operator.index',
                    default => 'user.index',
                };

                return redirect()->route($route)->with('error', 'Silakan lengkapi data profil Anda terlebih dahulu.');
            }
        }

        return $next($request);
    }
}
