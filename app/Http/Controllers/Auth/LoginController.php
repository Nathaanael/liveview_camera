<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function show(): View
    {
        return view('pages.auth.login', ['title' => __('login.title')]);
    }

    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $throttleKey = Str::lower($credentials['email']).'|'.$request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            throw ValidationException::withMessages([
                'email' => __('login.throttle', ['seconds' => RateLimiter::availableIn($throttleKey)]),
            ]);
        }

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            RateLimiter::hit($throttleKey);

            throw ValidationException::withMessages([
                'email' => __('login.failed'),
            ]);
        }

        RateLimiter::clear($throttleKey);

        $home = self::homeFor($request->user());

        if ($home === null) {
            Auth::logout();

            throw ValidationException::withMessages([
                'email' => __('login.noAccess'),
            ]);
        }

        $request->session()->regenerate();

        return redirect()->intended($home);
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /**
     * Landing page per role. Add other roles here when their pages exist.
     */
    public static function homeFor(?User $user): ?string
    {
        return match ($user?->role) {
            'admin' => route('admin.dashboard'),
            default => null,
        };
    }
}
