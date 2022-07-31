<?php

namespace App\Http\Controllers\Envs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Models\Reminder;
use App\Http\Resources\UserNormalResource;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index (Request $request) {

        return Inertia::render('Dashboard', [
            'auth' => [
                'user' => new UserNormalResource(Auth::guard('web')->user()),
                'logged' => Auth::guard('web')->check(),
                'verified' => Auth::guard('web')->user()->hasVerifiedEmail()
            ],
        ]);
    }
}
