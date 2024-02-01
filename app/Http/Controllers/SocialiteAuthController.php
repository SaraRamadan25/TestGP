<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialiteAuthController extends Controller
{
    public function redirectToGitHub(): JsonResponse
    {
        $redirectUri = Socialite::driver('github')->stateless()->redirect()->getTargetUrl();

        return response()->json(['redirect_uri' => $redirectUri]);
    }

    public function handleGitHubCallback(): RedirectResponse
    {
            $githubUser = Socialite::driver('github')->stateless()->user();
            $user = User::where('email', $githubUser->getEmail())->first();

            if (!$user) {
                $user = new User();
                $user->name = $githubUser->getName();
                $user->email = $githubUser->getEmail();
                $user->save();
            }

            Auth::login($user);

            return redirect()->route('home');

    }
}
