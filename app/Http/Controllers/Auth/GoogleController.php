<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\GoogleProvider;
use Laravel\Socialite\Two\User as SocialiteGoogleUser;
use PDOException;

final class GoogleController
{
    public function create(): RedirectResponse
    {
        /** @var GoogleProvider $provider */
        $provider = Socialite::driver('google');

        return $provider
            ->with(['hl' => App::getLocale()])
            ->redirect();
    }

    public function store(): RedirectResponse
    {
        /** @var SocialiteGoogleUser $googleUser */
        $googleUser = Socialite::driver('google')->user();

        try {
            $user = User::updateOrCreate([
                'google_id' => $googleUser->getId(),
            ], [
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'avatar' => $googleUser->getAvatar(),
            ]);

            if ($user->wasRecentlyCreated) {
                $user->markEmailAsVerified();
                $user->save();
            }

            Auth::login($user, true);

            return redirect()->intended(route('dashboard'));

        } catch (PDOException) {
            return redirect()->route('login')->with('error', 'Indirizzo email già registrato.');
        }
    }
}
