<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class AuthSetup
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $redirectToRoute = null) {
        // Check if a User is logged in, and whether they have setup their account yet
        if ($request->user() && $request->user()->created_at == $request->user()->updated_at) {
            // Check if they are currently at the GetStarted page
            if($request->route()->uri != 'GetStarted') {
                return Redirect::route('auth.setup');
            } else {
                // Otherwise show the page as normal
                return $next($request);
            }
        }
        // Otherwise, show the login screen
        return Redirect::route('login');
    }
}
