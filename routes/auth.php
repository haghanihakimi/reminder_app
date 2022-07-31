<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

//Registration Routes
Route::controller(RegisteredUserController::class)->group(function () {
    Route::get('register', 'index')->middleware(['guest'])->name('register');
    Route::post('register', 'store')->middleware(['guest', 'throttle:5,1'])->name('register.create');
    //Google Login
    Route::get('/login/google', 'googleAuth')->middleware(['guest'])->name('login.google.auth');
    Route::get('/login/google/redirect', 'googleLogin')->middleware(['guest'])->name('login.google.login');
    //GitHub Login
    Route::get('/login/github', 'githubAuth')->middleware(['guest'])->name('login.github.auth');
    Route::get('/login/github/redirect', 'githubLogin')->middleware(['guest'])->name('login.github.login');
});

//Login & authentication routes
Route::controller (AuthenticatedSessionController::class)->group(function () {
    Route::get('login', 'index')->middleware(['guest'])->name('login');
    Route::post('login', 'store')->middleware(['guest', 'throttle:5,1'])->name('login.logging');
    Route::post('logout', 'destroy')->middleware(['auth', 'throttle:3,1'])->name('logout');
});

//Reset Password Send Link routes
Route::controller(PasswordResetLinkController::class)->group(function () {
    Route::get('forgot-password', 'index')->middleware(['guest'])->name('password.request');
    Route::post('forgot-password', 'store')->middleware(['throttle:5,1'])->name('password.email');
});

//Email Verification routes
Route::controller(VerifyEmailController::class)->group(function () {
    Route::get('/verify-email', 'index')->middleware(['auth'])->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', 'verify')->middleware(['auth'])->name('verification.verify');
    Route::post('/email/verification-notification', 'sendNotification')->middleware(['auth'])->name('verification.send');
});

//Reset Password Process routes
Route::controller(NewPasswordController::class)->group(function () {
    Route::get('/reset-password/{token}', 'index')->middleware(['throttle:5,1'])->name('password.reset');
    Route::post('/reset-password', 'store')->middleware(['throttle:5,1'])->name('password.update');
});


Route::controller(ConfirmablePasswordController::class)->group(function () {
    Route::get('confirm-password', 'show')->name('password.confirm');
    Route::post('confirm-password', 'store')->middleware(['throttle:1,1'])->name('password.confirm.store');
});