<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // return $next($request);
                // Periksa apakah pengguna sudah login DAN memiliki peran 'admin'
        if (Auth::check() && Auth::user()->hasRole('admin')) {
            // Jika ya, izinkan akses
            return $next($request);
        }
        
        // Jika tidak, tolak akses dengan halaman error 403 (Forbidden)
        return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk mengakses halaman admin.');
    }
}
