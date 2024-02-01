<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class FaceBookController extends Controller
{
    public function __construct()
{
    $this->middleware('guest')->except('logout');
}

    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->stateless()->redirect();
    }

    public function handleFacebookCallback(): RedirectResponse
    {
        $user = Socialite::driver('facebook')->user();

        $this->_registerOrLoginUser($user);

        return redirect()->route('home');
    }

    protected function _registerOrLoginUser($data): void
    {
        $user = User::where('email', '=', $data->email)->first();
        if (!$user) {
            $user = new User();
            $user->name = $data->name;
            $user->email = $data->email;
            $user->provider_id = $data->id;
            $user->save();
        }
        Auth::login($user);
    }
}
