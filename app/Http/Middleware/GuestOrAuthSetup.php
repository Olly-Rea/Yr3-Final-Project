<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class GuestOrAuthSetup
{
    /**
     * Handle an incoming request.
     *
     * @param Request    $request
     * @param \Closure   $next
     * @param mixed|null $redirectToRoute
     *
     * @return mixed
     */
    public function handle($request, \Closure $next, $redirectToRoute = null): mixed
    {
        // Check if a User is logged in
        if ($request->user()) {
            // Boolean if user preferences haven't been setup yet
            $setupRequired = $request->user()->profile->spice_pref === -1 && $request->user()->profile->sweet_pref === -1
                && $request->user()->profile->sour_pref === -1 && $request->user()->profile->diff_pref === -1;
            // Check whether they have setup their account yet
            if ($setupRequired) {
                // Check if they are currently at the GetStarted page
                if ($request->route()->uri !== 'GetStarted') {
                    return Redirect::route('auth.setup');
                }
            // Otherwise, show the next screen
            } else {
                return $next($request);
            }
        }

        // Otherwise - if user is a guest - show the page as normal
        return $next($request);
    }
}
