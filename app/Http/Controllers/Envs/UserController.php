<?php

namespace App\Http\Controllers\Envs;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\User;
use App\Http\Resources\UserProfileResource;
use Inertia\Inertia;
use Carbon\Carbon;

class UserController extends Controller
{
    public function userProfile () {
        return Inertia::render('Profile', [
            'auth' => [
                'user' => new UserProfileResource(Auth::guard('web')->user()),
                'logged' => Auth::guard('web')->check(),
                'verified' => Auth::guard('web')->user()->hasVerifiedEmail()
            ],
        ]);
    }

    public function saveChanges (Request $request) {
        $this->validateEntries($request, Auth::guard('web')->user());

        $user = User::find(Auth::guard('web')->user()->id);

        $this->update($user, $request);

        return back()->with('profile_changes_status', [
            'message' => 'You profile updated successfully.',
            'updated_email' => Auth::guard()->user()->email !== $request->email_confirmation, 
            'user' => new UserProfileResource($user),
        ]);
    }

    private function validateEntries ($request, $user) {
        return $request->validate([
            'first_name' => ['required', 'string', 'min:3', 'max:64', 'regex:/^[a-zA-Z]+$/'],
            'surname' => ['required', 'string', 'min:3', 'max:64', 'regex:/^[a-zA-Z]+$/'],
            'email' => ['email', 'min:10', 'max:64', 'confirmed', Rule::unique('users')->ignore($user->id)],
            'birthdate' => ['nullable', 'date', 'before:12 years ago'],
            'gender' => ['nullable', 'in:female,male,null']
        ]);
    }

    private function update ($user, $request) {
        return $user->update([
            'fname' => $request->first_name,
            'sname' => $request->surname,
            'email' => (!empty($request->email)) ? $request->email : $user->email,
            'email_verified_at' => ($request->email !== $user->email) ? null : $user->email_verified_at,
            'birthdate' => (!empty($request->birthdate)) ? Carbon::parse($request->birthdate)->format('Y-m-d') : null,
            'gender' => (in_array($request->gender, ['female', 'male'])) ? $request->gender : null
        ]);
    }
}
