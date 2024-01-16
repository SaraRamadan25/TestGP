<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
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

    // Facebook login
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->stateless()->redirect();
    }

    // Facebook callback
    public function handleFacebookCallback()
    {
        dd('hello');
        $user = Socialite::driver('facebook')->user();

        $this->_registerOrLoginUser($user);

        // Return home after login
        return redirect()->route('home');
    }

    protected function _registerOrLoginUser($data)
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
