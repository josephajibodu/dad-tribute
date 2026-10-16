<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminIsSignedIn
{
    /**
     * Let the request through only once the admin password has been entered on the sign-in page.
     * Without a password configured, the admin pages do not exist.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (blank(config('tribute.admin_password'))) {
            abort(404);
        }

        if ($request->session()->get('admin_signed_in') !== true) {
            return redirect()->guest(route('admin.login'));
        }

        $response = $next($request);
        $response->headers->set('X-Robots-Tag', 'noindex, nofollow');

        return $response;
    }
}
