<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
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

        $request->session()->regenerate();

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        // Redirect to ecommerce-app's shared logout endpoint to complete the SLO chain.
        $ecommerceAppUrl = rtrim(env('ECOMMERCE_APP_URL', 'http://ecommerce.localhost'), '/');
        // Pass our login page as the 'final' redirect, so ecommerce-app's shared logout knows where to send the user
        $logoutChainUrl = $ecommerceAppUrl . '/shared-logout'; 
        // If we wanted ecommerce-app to redirect back to foodpanda's login:
        // $finalRedirectUrl = route('login'); // foodpanda's login
        // $logoutChainUrl = $ecommerceAppUrl . '/shared-logout?redirect_after_ecommerce_logout=' . urlencode($finalRedirectUrl);


        \Illuminate\Support\Facades\Log::info('foodpanda-app: Logging out locally and redirecting to ecommerce-app for SLO: ' . $logoutChainUrl);

        return redirect()->away($logoutChainUrl);
    }
}
