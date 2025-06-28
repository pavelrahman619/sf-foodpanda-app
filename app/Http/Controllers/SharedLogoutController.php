<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;

class SharedLogoutController extends Controller
{
    /**

     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleSharedLogout(Request $request)
    {
        // Ensure local session is terminated
        if (Auth::guard('web')->check()) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        } else {
        }

        // After logging out locally, redirect to the URL provided by ecommerce-app
        $finalRedirectUrl = $request->query('redirect_url');

        if ($finalRedirectUrl) {
            // Basic validation: Ensure the redirect URL is to a trusted domain (ecommerce-app or self)
            $ecommerceAppBaseUrl = rtrim(env('ECOMMERCE_APP_URL', 'http://ecommerce.localhost'), '/');
            $selfBaseUrl = rtrim(url('/'), '/'); // foodpanda-app's own base URL

            if (str_starts_with($finalRedirectUrl, $ecommerceAppBaseUrl) || str_starts_with($finalRedirectUrl, $selfBaseUrl)) {
                return redirect()->away($finalRedirectUrl);
            } else {
            }
        }
        
        return redirect()->route('login'); // Default to foodpanda-app's own login page
    }
}
