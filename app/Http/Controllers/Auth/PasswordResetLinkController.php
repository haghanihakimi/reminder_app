<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use Inertia\Inertia;
use Carbon\Carbon;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        return Inertia::render('Auth/ForgotPassword');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'min:11', 'max: 64'],
        ]);

        $guest = User::where('email', $request->email)->first();

        if (!empty($guest) && $guest->hasVerifiedEmail()) {
            $status = Password::sendResetLink(
                $request->only('email')
            );

            if ($status == Password::RESET_LINK_SENT) {
                $request->session()->put('resetPasswordSession', now()->addMinutes(20));
                
                return back()->with('status', ["code" => 200, "message" => __($status)]);
            }

            return back()->with('status', ["code" => 500, "message" => __($status)]);
        }

        return back()->with('status', ["code" => 404, "message" => "Either given email address does not exist in our record or it is not verified. Please verify your identity before submitting a password reset request."]);
    }
}
