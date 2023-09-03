<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application as AppContract;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Foundation\Application;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\RedirectResponse;

class ExternalAuthController extends Controller
{
    /**
     * Redirects to the provider auth page
     * @param string $provider
     * @return RedirectResponse|\Illuminate\Http\RedirectResponse
     */
    public function redirectToProvider(string $provider): RedirectResponse|\Illuminate\Http\RedirectResponse
    {
        return Socialite::driver($provider)->stateless()->redirect();
    }

    /**
     * Handles callback from external OAuth provider and creates a user if not found.
     * @param string $provider
     * @return Application|Response|AppContract|ResponseFactory
     */
    public function handleProviderCallback(string $provider): Application|Response|AppContract|ResponseFactory
    {
        if ($provider === 'twitter') {
            // twitter does not support stateless.
            // Note that we are not using session cookies since this is a stateless api.
            $user = Socialite::driver($provider)->user();
        } else {
            $user = Socialite::driver($provider)->stateless()->user();
        }

        // Highly unlikely that $user would be null and without Socialite throwing an error or a message
        // But... it's best to be safe than sorry
        if (!$user) {
            return response(['message' => 'User not found'], 401);
        }

        // syntatic sugar for PHPDocs to ensure IDE does not show warning when using createToken.
        /** @var User $localUser */
        $localUser = User::query()->firstOrCreate(
            ['email' => $user->email],
            [
                'name' => $user->name,
                'password' => Hash::make(Str::random(16)),
            ]
        );

        $localUser->socialAccount()->updateOrCreate(
            ['user_id' => $localUser->id, 'provider' => $provider],
            [
                'provider_user_id' => $user->getId(),
                'token' => $user->token,
                'refresh_token' => $user->refreshToken ?? null,
                'expires_in' => $user->expiresIn ? Date::now()->addSeconds($user->expiresIn) : null,
            ]
        );

        $token = $localUser->createToken('authToken')->accessToken;

        return response(['token' => $token, 'user' => $localUser]);
    }
}
