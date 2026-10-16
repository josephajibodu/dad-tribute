<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class RequireAdminPassword
{
    /**
     * How many wrong passwords one visitor may try in a minute before being made to wait.
     */
    private const MaxWrongPasswordsPerMinute = 10;

    /**
     * Let the request through only with the admin password, asked for by the browser's own sign-in prompt.
     * Without a password configured, the admin pages do not exist. Only a wrong password that was actually
     * typed counts towards the limit; a visit with none, or a browser resending empty credentials, never does.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $password = config('tribute.admin_password');

        if (blank($password)) {
            abort(404);
        }

        $limiterKey = 'admin-password:'.$request->ip();

        if (RateLimiter::tooManyAttempts($limiterKey, self::MaxWrongPasswordsPerMinute)) {
            return response('Too many wrong passwords. Please wait a minute and try again.', 429, [
                'Retry-After' => RateLimiter::availableIn($limiterKey),
            ]);
        }

        if (! hash_equals((string) $password, (string) $request->getPassword())) {
            if (filled($request->getPassword())) {
                RateLimiter::hit($limiterKey);
            }

            return response('A password is needed to see this page.', 401, [
                'WWW-Authenticate' => 'Basic realm="Tribute admin", charset="UTF-8"',
            ]);
        }

        $response = $next($request);
        $response->headers->set('X-Robots-Tag', 'noindex, nofollow');

        return $response;
    }
}
