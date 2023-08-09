<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class MustBeLoggedIn
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next) // Clousre: Closures are anonymous functions that don't belong to any class or object. Closures don't have specified names and can also access variables outside of scope without using any global variables
    {
        if (auth()->check()) {
            return $next($request);
        }
        return redirect('/')->with('failure', 'You must be logged in');
    }
}
