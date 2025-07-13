<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnalyticsAccessMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        $user = Auth::user();
        
        // Cek apakah user memiliki role
        if (!$user->role) {
            return redirect()->route('access-denied');
        }

        // Cek role untuk akses dashboard analitik
        if (!in_array($user->role->name, ['dept_head', 'section_head', 'admin'])) {
            return redirect()->route('access-denied');
        }

        return $next($request);
    }
} 