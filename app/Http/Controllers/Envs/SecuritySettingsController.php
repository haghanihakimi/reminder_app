<?php

namespace App\Http\Controllers\Envs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\User;
use App\Http\Resources\UserProfileResource;
use App\Jobs\DeleteAccountJob;
use Inertia\Inertia;
use Carbon\Carbon;

class SecuritySettingsController extends Controller
{
    public function securityIndex () {
        return Inertia::render('SecuritySettings', [
            'auth' => [
                'user' => new UserProfileResource(Auth::guard('web')->user()),
                'logged' => Auth::guard('web')->check(),
                'verified' => Auth::guard('web')->user()->hasVerifiedEmail()
            ],
        ]);
    }

    public function mailNotification () {
        if (RateLimiter::remaining('mail_notification_throttle:'.Auth::guard('web')->user()->id, $perMinute = 10)) {
            RateLimiter::hit('mail_notification_throttle:'.Auth::guard('web')->user()->id);
            
            $user = User::find(Auth::guard('web')->user()->id);

            $user->mail_notification = $user->mail_notification ? false : true;
            $user->save();

            return back()->with('email_notification', $user->mail_notification);
        }

        return back()->with('mail_notification_failure', [
            'throttle' => RateLimiter::remaining('mail_notification_throttle:'.Auth::guard('web')->user()->id, $perMinute = 10),
            'message' => "You tried too many times in a minute! Please wait before you try again."
        ]);
    }

    public function deactivateUser (Request $request) {
        $user = User::find(Auth::guard('web')->user()->id);

        DeleteAccountJob::dispatch($user);

        $user->delete();

        $this->logout($request);

        return redirect()->route('login')->with('status', 'Your account is deleted. You can reactivate your account before 14 days.');
    }

    private function logout ($request) {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();
    }
}
