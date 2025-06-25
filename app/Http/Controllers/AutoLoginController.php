<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; // For logging

class AutoLoginController extends Controller
{
    /**
     * Handle the auto-login attempt after token validation by middleware.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function performAutoLogin(Request $request)
    {
        if (Auth::guard('web')->check()) {
            Log::info('AutoLoginController: User ' . Auth::id() . ' is authenticated. Redirecting to dashboard.');

            return redirect()->route('dashboard'); // Assuming 'dashboard' is a named route
        } else {
            Log::warning('AutoLoginController: Auto-login failed or token was invalid/not processed. Redirecting to login.');

            return redirect()->route('login')->with('error', 'Auto-login failed. Please log in manually.'); // Assuming 'login' is a named route
        }
    }
}
