<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ReminderCloseDeadline;
use Illuminate\Support\Str;
use Carbon\Carbon;

class NotifyCloseDeadlineJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $user;
    protected $reminder;

    public function __construct($user, $reminder)
    {
        $this->user = $user;
        $this->reminder = $reminder;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->user->mail_notification) {
            $data = [
                "header" => "Hi ".$this->user->fname.",<br/><br/>Less than 3 days left to your reminder!<br/><br/>
                    <strong>Reminder Title: </strong>".$this->reminder->reminder_title."<br/>
                    <strong>Reminder Descriptions: </strong>".$this->reminder->reminder_descriptions."<br/>
                    <strong>Reminder Date: </strong>".Carbon::parse($this->reminder->due_date)->format("D, d/m/Y")."<br/>
                    <strong>Reminder Time: </strong>".Carbon::parse($this->reminder->due_date)->format("H:i")."",
                "button" => "View Reminder",
                "link" => route('view.user', $this->reminder->pid),
            ];
    
            $message = "3 days left to ".Str::of($this->reminder->reminder_title)->limit(24, '...')." reminder.";
    
            Notification::send($this->user, new ReminderCloseDeadline($this->user, $this->reminder, 'a reminder is close to deadline', $message, 'reminders', $data, null));
        }
    }
}
