<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Notifications\ResponseNotification;
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

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        Auth::user()->notify(new ResponseNotification('success' , 'You have logged in successfully.'));
        if (Auth::user()->hasRole('admin') || Auth::user()->hasRole('superadmin')) {
            return redirect()->intended(route('admin.dashboard', absolute: false));
        }
        elseif (Auth::user()->hasRole('teacher')) {
            return redirect()->intended(route('teacher.dashboard', absolute: false));
        }
        elseif (Auth::user()->hasRole('parent')) {
            return redirect()->intended(route('parent.dashboard', absolute: false));
        }
        else {
            return redirect()->intended(route('student.dashboard', absolute: false));
        }
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
