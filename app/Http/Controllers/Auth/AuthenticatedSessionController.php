<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }
    public function adminCreate():  View
    {
        return view('auth.admin-login');
    }
    public function store(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->only('email', 'password');
        $user = User::where('email', $credentials['email'])->first();

        if (!$user || $user->deleted_at !== null) {
            return redirect()->route('login')
                ->withErrors(['error' => 'This account is no longer active.']);
        }
        $request->authenticate();
        $request->session()->regenerate();

        $user = Auth::user();

        if ($user->role_id == 1 || $user->role_id == 2) {
            Auth::logout();
            return redirect()->route('login')->withErrors(['error' => 'not authorized']);
        }

        if ($user->role_id == 3) {
            return redirect()->back();
        }
        Auth::logout();
        return redirect()->route('login')->withErrors(['error' => 'Unknown data']);
    }

    public function adminStore(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->only('email', 'password');
        $user = User::where('email', $credentials['email'])->first();

        if (!$user || $user->deleted_at !== null) {
            return redirect()->route('superadmin.auth.login')
                ->withErrors(['error' => 'This account is no longer active.']);
        }
        $request->authenticate();
        $request->session()->regenerate();

        $user = Auth::user();

        if ($user->role_id == 1 || $user->role_id == 2) {
            return redirect()->intended(route('superadmin.dashboard'));
        }

        if ($user->role_id == 3) {
            Auth::logout();
            return redirect()->route('superadmin.auth.login')->withErrors(['error' => 'not authorized']);
        }
        Auth::logout();
        return redirect()->route('superadmin.auth.login')->withErrors(['error' => 'Unknown data']);
    }


    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
