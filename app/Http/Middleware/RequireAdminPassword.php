<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequireAdminPassword
{
    /**
     * Let the request through only with the admin password, asked for by the browser's own sign-in prompt.
     * Without a password configured, the admin pages do not exist.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $password = config('tribute.admin_password');

        if (blank($password)) {
            abort(404);
        }

        if (! hash_equals((string) $password, (string) $request->getPassword())) {
            return response('A password is needed to see this page.', 401, [
                'WWW-Authenticate' => 'Basic realm="Tribute admin", charset="UTF-8"',
            ]);
        }

        $response = $next($request);
        $response->headers->set('X-Robots-Tag', 'noindex, nofollow');

        return $response;
    }
}
