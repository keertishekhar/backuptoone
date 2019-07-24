<?php

namespace App\Http\Middleware;

use Closure;
use Session;
class checksessionMiddleware
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
        if(Session::has('users')){
            return redirect('/profile');
        }
        return $next($request);
    }
}
