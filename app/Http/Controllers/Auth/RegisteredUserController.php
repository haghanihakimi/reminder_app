<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use App\Jobs\NewAccountJob;
use App\Jobs\WelcomeJob;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Carbon\Carbon;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        
        $this->validation($request);

        $user = User::create([
            'uid' => Str::uuid()->toString(),
            'fname' => Str::of($request->first_name)->ucfirst(),
            'sname' => Str::of($request->surname)->ucfirst(),
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        NewAccountJob::dispatch($user);

        Auth::login($user, true);

        return redirect(RouteServiceProvider::HOME);
    }

    /**
     * Register & login using Google OAuth
     */
    public function googleAuth () {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Register & login using Google OAuth
     */
    public function googleLogin () {
        $user = Socialite::driver('google')->user();

        $newUser = $this->createUser($user->user['email'], $user->user['given_name'], $user->user['family_name'], $user->user['verified_email'], 'Google');

        if ($newUser->wasRecentlyCreated && !$newUser->hasVerifiedEmail()) {
            NewAccountJob::dispatch($newUser);
        } elseif ($newUser->wasRecentlyCreated && $newUser->hasVerifiedEmail()) {
            WelcomeJob::dispatch($newUser);
        }

        if (! Auth::login($newUser, true)) {
            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Register & login using GitHub OAuth
     */
    public function githubAuth () {
        return Socialite::driver('github')->redirect();
    }

    /**
     * Register & login using GitHub OAuth
     */
    public function githubLogin () {
        $user = Socialite::driver('github')->user();

        $newUser = $this->createUser($user->user['email'], Str::random(8), Str::random(8), false, 'GitHub');

        if ($newUser->wasRecentlyCreated && !$newUser->hasVerifiedEmail()) {
            NewAccountJob::dispatch($newUser);
        } elseif ($newUser->wasRecentlyCreated && $newUser->hasVerifiedEmail()) {
            WelcomeJob::dispatch($newUser);
        }

        if (! Auth::login($newUser, true)) {
            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    public function createUser ($email, $fname, $sname, $verification, $created_by) {
        $newUser = User::firstOrCreate([
            'email' => $email
        ], [
            'uid' => Str::uuid()->getHex()->toString(),
            'fname' => Str::of($fname)->ucfirst(),
            'sname' => Str::of($sname)->ucfirst(),
            'email' => $email,
            'email_verified_at' => ($verification) ? now() : null,
            'password' => Hash::make(Str::random(32)),
            'created_by' => $created_by
        ]);
        return $newUser;
    }

    private function validation ($request) {
        return $request->validate([
            'first_name' => ['required', 'string', 'min:3', 'max:64', 'regex:/(^([a-zA-Z]+)(\d+)?$)/u'],
            'surname' => ['required', 'string', 'min:3', 'max:64', 'regex:/(^([a-zA-Z]+)(\d+)?$)/u'],
            'email' => ['required', 'email', 'min:10', 'max:64', 'confirmed', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }
}
