<?php

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Auth;
use App\Models\User;


Broadcast::channel('reminder.close.deadline.{id}', function ($user, $id) {
    return User::find($id);
});

Broadcast::channel('reminder.deadline.{id}', function ($user, $id) {
    return User::find($id);
});

Broadcast::channel('reminder.soft.delete.{id}', function ($user, $id) {
    return User::find($id);
});

Broadcast::channel('notification.{id}', function ($user, $id) {
    return User::where('uid', $id)->exists();
});
