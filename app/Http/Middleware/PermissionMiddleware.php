<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $permissionName)
    {
        $user = Auth::user();

        if($user->hasPermissionTo($permissionName))
        {
            return $next($request);
        }else{
            return redirect('/login');
        }
    }
}
