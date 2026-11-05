<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = auth()->user();

        if (!empty($roles) && !in_array($user->role, $roles, true)) {
            // If admin tries to access customer route or customer tries to access admin route
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard')->with('error', 'Halaman ini khusus untuk customer.');
            }

            abort(403, 'Akses ditolak: Anda tidak memiliki hak akses ke halaman ini.');
        }

        return $next($request);
    }
}

