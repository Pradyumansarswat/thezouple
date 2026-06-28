<?php

namespace App\Http\Middleware;

use Closure;

use Auth;
use Redirect;

class AuthAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
       if (Auth::guard('admin')->guest()){
            return Redirect::route('admin.login');
        }

        $admin = Auth::guard('admin')->user();
        if (!$admin || $admin->user_role !== 'ADMIN' || $admin->is_active !== 'ACTIVE') {
            Auth::guard('admin')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return Redirect::route('admin.login');
        }

        return $next($request);
    }
}
