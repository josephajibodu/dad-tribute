<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class SessionController extends Controller
{
    /**
     * How many wrong passwords one visitor may submit in a minute before being made to wait.
     */
    private const MaxWrongPasswordsPerMinute = 10;

    public function create(Request $request): View|RedirectResponse
    {
        $this->abortUnlessConfigured();

        if ($request->session()->get('admin_signed_in') === true) {
            return redirect()->route('admin.tributes');
        }

        return view('admin.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $this->abortUnlessConfigured();

        $request->validate(['password' => ['required', 'string']]);

        $limiterKey = 'admin-password:'.$request->ip();

        if (RateLimiter::tooManyAttempts($limiterKey, self::MaxWrongPasswordsPerMinute)) {
            throw ValidationException::withMessages([
                'password' => 'Too many wrong passwords. Please wait '.RateLimiter::availableIn($limiterKey).' seconds and try again.',
            ]);
        }

        if (! hash_equals((string) config('tribute.admin_password'), $request->string('password')->toString())) {
            RateLimiter::hit($limiterKey);

            throw ValidationException::withMessages([
                'password' => 'That password is not right.',
            ]);
        }

        RateLimiter::clear($limiterKey);
        $request->session()->regenerate();
        $request->session()->put('admin_signed_in', true);

        return redirect()->intended(route('admin.tributes'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->session()->forget('admin_signed_in');
        $request->session()->regenerate();

        return redirect()->route('admin.login');
    }

    private function abortUnlessConfigured(): void
    {
        if (blank(config('tribute.admin_password'))) {
            abort(404);
        }
    }
}
