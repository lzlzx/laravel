<?php

namespace App\Http\Middleware;

use Closure;

class IsLogin
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
        if (session()->has('user'))
        {
            return $next($request);
        }
        else
        {
            return redirect('admin/login')->with('msg','请您先登录');
        }

    }
}
