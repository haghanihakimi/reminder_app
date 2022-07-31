<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Envs\HomeController;
use App\Http\Controllers\Envs\DashboardController;
use App\Http\Controllers\Envs\RemindersController;
use App\Http\Controllers\Envs\NotificationsController;
use App\Http\Controllers\Envs\UserController;
use App\Http\Controllers\Envs\SecuritySettingsController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

//Home Routes
Route::controller(HomeController::class)->group(function () {
    Route::get('/', 'index')->middleware(['guest'])->name('root');
    Route::get('/home', 'index')->middleware(['guest'])->name('home');
});

//Dashboard Routes
Route::controller(DashboardController::class)->group(function () { 
    Route::get('/dashboard', 'index')->middleware(['auth', 'verified'])->name('dashboard');
});

//Reminders Routes
Route::controller(RemindersController::class)->group(function () {
    //Route Views
    Route::get('/reminder/v/create/new', 'createView')->middleware(['auth', 'verified'])->name('reminders.create');
    Route::get('/reminder/v/edit/{reminder}', 'editIndex')->middleware(['auth', 'verified'])->name('reminders.edit');
    Route::get('/reminder/v/ignore/{reminder}', 'ignoreIndex')->middleware(['auth', 'verified'])->name('reminders.ignore');
    Route::get('/reminder/v/delete/{reminder}', 'deleteIndex')->middleware(['auth', 'verified'])->name('reminders.delete');
    Route::get('/reminders/view/list', 'listReminders')->middleware(['auth', 'throttle:15,1'])->name('reminders.list');
    Route::get('/reminders/view/ignored', 'ignoredsView')->middleware(['auth', 'throttle:15,1'])->name('reminders.view.ignoreds');
    Route::get('/reminder/view/share/{reminder}', 'sharedView')->middleware(['signed', 'throttle:15,1'])->name('share.share.view');
    Route::get('/reminder/view/user/{reminder}', 'reminderUserView')->middleware(['auth', 'throttle:15,1'])->name('view.user');

    //Route Actions
    Route::post('/reminder/act/create', 'create')->middleware(['auth', 'verified', 'throttle:15,1'])->name('reminders.act.create');
    Route::delete('/reminder/{origin}/act/delete/{reminder}', 'destroy')->middleware(['auth', 'verified', 'throttle:15,1'])->name('reminders.act.delete');
    Route::put('/reminder/act/edit/{reminder}', 'saveChanges')->middleware(['auth', 'verified', 'throttle:15,1'])->name('reminders.act.edit');
    Route::delete('/reminder/act/ignore/{reminder}', 'ignore')->middleware(['auth', 'verified', 'throttle:15,1'])->name('reminders.act.ignore');
    Route::put('/reminder/act/restore/{reminder}', 'restore')->middleware(['auth', 'verified', 'throttle:15,1'])->name('reminders.act.restore');
    Route::get('/reminders/view/ignored/list', 'ignoredsList')->middleware(['auth', 'throttle:15,1'])->name('reminders.list.ignore');
    Route::get('/reminders/search/reminder', 'searchReminders')->middleware(['auth', 'throttle:500,1'])->name('reminder.search');
});

//Notifications Routes
Route::controller(NotificationsController::class)->group(function () {
    Route::get('/notifications/catch/notification', 'getNotifications')->middleware(['auth'])->name('notifications.catch');
    Route::get('/notifications/count', 'notifyCount')->middleware(['auth'])->name('notifications.count');

    Route::post('/notifications/user/dissmiss/all', 'dismissAllNotifications')->middleware(['auth', 'throttle:3,1'])->name('notifications.dismiss.all');
});

//Profile Routes
Route::controller(UserController::class)->group(function () {
    Route::get('/user/profile', 'userProfile')->middleware(['auth'])->name('user.profile.view');

    Route::post('/user/profile/changes/save', 'saveChanges')->middleware(['auth', 'throttle:5,1'])->name('user.profile.save');
});

//Account Settings Routes
Route::controller(SecuritySettingsController::class)->group(function () {
    Route::get('/user/profile/settings/security/', 'securityIndex')->middleware(['auth'])->name('user.settings.security.view');

    Route::post('/user/account/settings/notifications/mail/notification', 'mailNotification')->middleware(['auth'])->name('mail.notifications.toggle');
    Route::delete('/user/account/delete', 'deactivateUser')->middleware(['auth'])->name('user.account.delete');
});

require __DIR__.'/auth.php';
