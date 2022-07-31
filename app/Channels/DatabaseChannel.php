<?php

namespace App\Channels;

use Illuminate\Notifications\Channels\DatabaseChannel as IlluminateDatabaseChannel;
use Illuminate\Notifications\Notification;

class DatabaseChannel extends IlluminateDatabaseChannel
{
    /**
     * Send the given notification.
     *
     * @param mixed                                  $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function buildPayload($notifiable, Notification $notification)
    {
        return [
            'id' => $notification->id,
            'user_id' => $notification->getUser()->id,
            'type' => $notification->getType(),
            'title' => $notification->getTitle(),
            'message' => $notification->getMessage(),
            'data' => $this->getData($notifiable, $notification),
            'read_at' => $notification->getReadStatus(),
        ];
    }
}