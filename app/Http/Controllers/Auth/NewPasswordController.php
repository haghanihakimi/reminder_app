<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use App\Jobs\PasswordResetAlertJob;
use Inertia\Inertia;
use Carbon\Carbon;

class NewPasswordController extends Controller
{
    /**
     * Display the password reset view.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Inertia\Response
     */
    public function index(Request $request)
    {
        if (!$request->session()->exists('resetPasswordSession') || $request->session()->get('resetPasswordSession') <= now()->subMinute(20)) {
            $request->session()->remove('resetPasswordSession');

            return redirect()->route('root');
        }

        return Inertia::render('Auth/ResetPassword', [
            'auth' => Auth::guard('web')->check(),
            'email' => $request->email,
            'token' => $request->route('token'),
        ]);
    }

    /**
     * Handle an incoming new password request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => ['required', 'email', 'min:12', 'max:64'],
            'password' => ['required', 'confirmed', 'string', 'min: 8'],
        ]);

        $guest = User::where('email', $request->email)->first();

        if (!empty($guest) && $guest->hasVerifiedEmail()) {
            $status = Password::reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function ($user) use ($request) {
                    $user->forceFill([
                        'password' => Hash::make($request->password),
                        'remember_token' => Str::random(60),
                    ])->save();

                    event(new PasswordReset($user));
                    PasswordResetAlertJob::dispatch($user);
                }
            );

            if ($status == Password::PASSWORD_RESET) {
                $request->session()->remove('resetPasswordSession');
                
                return redirect()->route('login')->with('status', ["code" => 200, "message" => __($status)]);
            }

            return back()->with('status', ["code" => 500, "message" => __($status)]);
        }

        return back()->with('status', ["code" => 403, "message" => "Either given email address does not exist in our record or it is not verified. Please verify your identity before submitting a password reset request."]);
    }
}
