<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\RateLimiter;
use App\Jobs\AccountActivationJob;
use App\Models\User;
use Inertia\Inertia;

use Illuminate\Support\Facades\Config;

use Carbon\Carbon;

class VerifyEmailController extends Controller
{
    public function index()
    {
        return Auth::user()->hasVerifiedEmail()
                ? redirect('/dashboard')
                : Inertia::render('Auth/EmailVerifyNotice', [
                    'unverifiedMessage' => __('verification.prompt'),
                    'auth' => Auth::check(),
                    'verified' => Auth::user()->hasVerifiedEmail(),
                    'exhausted' => RateLimiter::remaining('verificationLink:'.Auth::user()->id, $perMinute = 5)
                ]);
    }

    /**
     * Mark the authenticated user's email address as verified.
     *
     * @param  \Illuminate\Foundation\Auth\EmailVerificationRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verify (EmailVerificationRequest $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(RouteServiceProvider::HOME.'?verified=1');
        }

        $request->fulfill();

        // if ($request->user()->markEmailAsVerified()) {
        //     event(new Verified($request->user()));
        // }

        return redirect()->intended(RouteServiceProvider::HOME.'?verified=1');
    }
    
    /**
     * Send a new email verification notification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendNotification()
    {
        if (RateLimiter::remaining('verificationLink:'.Auth::user()->id, $perMinute = 5)) {
            RateLimiter::hit('verificationLink:'.Auth::guard('web')->user()->id);
         
            
            if (Auth::guard('web')->user()->hasVerifiedEmail()) {
                return redirect()->intended(RouteServiceProvider::HOME);
            }

            $remainings = RateLimiter::remaining('verificationLink:'.Auth::guard('web')->user()->id, $perMinute = 5);

            AccountActivationJob::dispatch(User::find(Auth::guard('web')->user()->id));

            return back()->with("message", "Verification is has been sent to your email address");
        }
    }
}
