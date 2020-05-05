<?php

namespace App\Http\Middleware;
use Closure;
use Illuminate\Support\Facades\Auth;

class AuthLock {

    public function handle($request, Closure $next, $guard = 'admin')
    {

        if(!Auth::user()){
            return $next($request);
        }
        // If the user does not have this feature enabled, then just return next.
        if (!Auth::user()->hasLockoutTime()) {
            // Check if previous session was set, if so, remove it because we don't need it here.
            if (session('lock-expires-at')) {
                session()->forget('lock-expires-at');
            }
            return $next($request);
        }

        if ($lockExpiresAt = session('lock-expires-at')) {
            if ($lockExpiresAt < now()) {
                return redirect()->guest('admin/login');
            }
        }

        session(['lock-expires-at' => now()->addMinutes(Auth::user()->getLockoutTime())]);
        return $next($request);
    }

}
