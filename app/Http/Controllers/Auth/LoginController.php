<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{

    public function redirectToProvider()
    {
        return Socialite::driver('twitch')
            ->scopes(['channel:read:subscriptions'])
            ->redirect();
    }

    public function handleProviderCallback()
    {
        $twitchUser = Socialite::driver('twitch')->user();
        $user = User::firstOrNew(['id' => $twitchUser->id]);

        $user->name = $twitchUser->nickname;
        $user->avatar = $twitchUser->avatar;
        $user->twitch_token = $twitchUser->token;
        $user->twitch_refresh_token = $twitchUser->refreshToken;
        if (!$user->wasRecentlyCreated && $user->created_at === null) {
            $user->created_at = Carbon::now();
        }

        $user->save();
        Auth::login($user);

        return redirect()->route('dashboard');
    }
}
