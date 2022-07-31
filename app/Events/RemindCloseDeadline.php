<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RemindCloseDeadline implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $reminder;
    private $user;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    
    public function __construct($reminder, $user)
    {
        $this->reminder = $reminder;
        $this->user = $user;
    }
    
    public function broadcastOn()
    {
        return new PrivateChannel('reminder.close.deadline.'.$this->user->uid);
    }

    public function broadcastWith () {
        return [
            'message' => 'Less 3 days left to '.$this->reminder->reminder_title.' reminder.'
        ];
    }
}
