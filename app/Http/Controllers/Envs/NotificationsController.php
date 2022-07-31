<?php

namespace App\Http\Controllers\Envs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Notification;
use Carbon\Carbon;

class NotificationsController extends Controller
{
    public function getNotifications () {
        $notifications = Auth::guard('web')->user()
        ->notification()
        ->whereNull('read_at')
        ->where('created_at', '>=', now()->subDays(6))
        ->paginate(15)
        ->through(fn($notification) => [
            'id' => $notification->id,
            'notification_type' => $notification->type,
            'title' => $notification->title,
            'target' => json_decode($notification->data)->target_id,
            'receiver_id' => $notification->notifiable_id,
            'message' => $notification->message,
            'read' => $notification->read_at,
        ]);

        return $notifications;
    }
    
    public function notifyCount () {
        return Auth::guard('web')->user()
        ->notification()
        ->whereNull('read_at')
        ->where('created_at', '>=', now()->subDays(6))
        ->count();
    }

    public function dismissAllNotifications () {
        foreach ($this->getNotifications() as $notification) {
            $update = Notification::where('id', $notification['id'])
            ->where('notifiable_id', Auth::guard('web')->user()->id)
            ->whereNull('read_at')->update(['read_at' => now()]);
        }

        return back()->with('status', ['message' => "Notifications dismissed!"]);
    }
}
