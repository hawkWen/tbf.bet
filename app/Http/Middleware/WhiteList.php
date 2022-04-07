<?php

namespace App\Http\Middleware;

use Closure;

class WhiteList
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
        echo $request->ip();

        return $next($request);
    }
}
