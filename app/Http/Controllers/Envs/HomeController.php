<?php

namespace App\Http\Controllers\Envs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

class HomeController extends Controller
{
    private $auth;

    public function __construct () {
        $this->auth = Auth::guard('web')->check();
    }

    public function index () {
        return Inertia::render('Home', [
            'auth' => $this->auth,
        ]);
    }
}
