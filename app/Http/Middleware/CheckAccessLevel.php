<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckAccessLevel
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $accesslevel)
    {
        if(auth()->user()->access_level < $accesslevel){
            abort(403, 'Masz za niskie uprawnienia, żeby wykonać tą akcję');
        }

        return $next($request);
    }
}
