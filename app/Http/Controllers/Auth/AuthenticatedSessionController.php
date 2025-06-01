<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
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
    logger()->info('📥 Login submitted', $request->all());

    $request->authenticate();
    logger()->info('✅ Authentication passed');

    $request->session()->regenerate();

    $user = Auth::user();

    // ✅ Only system-level admin
    if ($user->is_admin) {
        return redirect()->route('admin.dashboard.index');
    }

    // ✅ Use roles for everything else
    if ($user->hasRole('breeder')) {
        return redirect()->route('breeder.dashboard');
    }

    if ($user->hasRole('clinic')) {
        return redirect()->route('clinic.dashboard');
    }

    if ($user->hasRole('coach')) {
        return redirect()->route('coach.dashboard');
    }

    // ❌ Default fallback: unknown role
    Auth::logout();
    return redirect()->route('login')->withErrors(['role' => 'شما به هیچ داشبوردی دسترسی ندارید.']);
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
