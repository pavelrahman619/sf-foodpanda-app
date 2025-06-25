<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; // For logging
use App\Models\EcommerceAppToken as PersonalAccessToken;
use App\Models\User; // Assuming App\Models\User is the user model in foodpanda-app

class AttemptLoginViaSharedToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Only attempt login if not already authenticated and token is present
        if (!Auth::guard('web')->check() && $request->has('token')) {
            $tokenString = $request->query('token');
            Log::info('AttemptLoginViaSharedToken: Received token: ' . substr($tokenString, 0, 20) . '...'); // Log part of token

            if (empty($tokenString)) {
                Log::warning('AttemptLoginViaSharedToken: Empty token received.');
                return $next($request); // Or redirect to login with error
            }
            
            $tokenInstance = PersonalAccessToken::findToken($tokenString);

            if ($tokenInstance) {
                Log::info('AttemptLoginViaSharedToken: Token found in database. Token ID: ' . $tokenInstance->id);
                $user = $tokenInstance->tokenable; // This is the user model from ecommerce-app's context initially.

                if ($user) {
                    $foodpandaUser = User::where('email', $user->email)->first();

                    if ($foodpandaUser) {
                        Auth::guard('web')->login($foodpandaUser);
                        Log::info('AttemptLoginViaSharedToken: User ' . $foodpandaUser->id . ' logged in successfully via shared token.');
                        
                        $tokenInstance->delete();
                        Log::info('AttemptLoginViaSharedToken: Token ' . $tokenInstance->id . ' (associated with user ' . $foodpandaUser->id . ') deleted after use.');

                    } else {
                        Log::warning('AttemptLoginViaSharedToken: User linked to token (ID: ' . $user->id . ') not found in foodpanda-app.');
                    }
                } else {
                    Log::warning('AttemptLoginViaSharedToken: Token found but no user (tokenable) associated with it. Token ID: ' . $tokenInstance->id);
                }
            } else {
                Log::warning('AttemptLoginViaSharedToken: Shared token not found or invalid.');
            }
        }

        return $next($request);
    }
}
