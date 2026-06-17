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
       if (Auth::guest()){
            return Redirect::to('masterAdmin/login');
        }else{
            if (Auth::check() && Auth::user()->user_role == 'FRONT') {
               if(Auth::user()->user_role == 'FRONT'){
                   return Redirect::to('dashboard');
               }else {
                   Auth::logout();
                   return Redirect::to('masterAdmin/login');
               }
            } else {
                return $next($request);
            }


        }
    }
}
