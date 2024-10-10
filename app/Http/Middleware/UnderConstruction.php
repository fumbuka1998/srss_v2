<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class UnderConstruction
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    // public function handle(Request $request, Closure $next)
    // {
    //     return $next($request);
    // }

    public function handle($request, Closure $next)
    {
        // Check if application is under construction
        if (file_exists(storage_path('framework/down'))) {
            return response()->view('under-construction');
        }

        return $next($request);
    }
}
