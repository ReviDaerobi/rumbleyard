<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\ActivityLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
class GoogleController extends Controller
{
    public function __construct(protected ActivityLogService $activityLog) {}

    public function redirect(): RedirectResponse
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    public function callback(): RedirectResponse
    {
         $googleUser = Socialite::driver('google')->stateless()->user();

        $user = User::query()->where('google_id', $googleUser->getId())
            ->orWhere('email', $googleUser->getEmail())
            ->first();

        if (! $user) {
            $user = User::create([
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'avatar' => $googleUser->getAvatar(),
                'password' => Hash::make(Str::random(32)),
                'email_verified_at' => now(),
            ]);
            $user->assignRole('customer');
            $this->activityLog->log($user, 'user.registered_google', $user);
        } else {
            $user->update([
                'google_id' => $googleUser->getId(),
                'avatar' => $googleUser->getAvatar() ?? $user->avatar,
            ]);
        }

        Auth::login($user, true);

        return redirect()->intended($user->dashboardRoute());
    }
}
