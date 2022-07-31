<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ReminderDeadline;
use Illuminate\Support\Str;
use Carbon\Carbon;

class NotifyDeadlineReminderJob implements ShouldQueue
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

    public function middleware()
    {
        return [(new WithoutOverlapping($this->user->id))->releaseAfter(10)];
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
                "header" => "Hi ".$this->user->fname.",<br/><br/>Your reminder for 
                <strong>".Carbon::parse($this->reminder->due_date)->format("D, d/m/Y - H:i")."</strong> 
                is now overdue.<br/><br/>
                <strong>Reminder Title: </strong>".$this->reminder->reminder_title."<br/>
                <strong>Reminder Descriptions: </strong>".$this->reminder->reminder_descriptions."<br/>
                <strong>Reminder Date: </strong>".Carbon::parse($this->reminder->due_date)->format("D, d/m/Y")."<br/>
                <strong>Reminder Time: </strong>".Carbon::parse($this->reminder->due_date)->format("H:i")."",
                "button" => "View Reminder",
                "link" => route('view.user', $this->reminder->pid),
            ];
    
            $message = "Your ".Str::of($this->reminder->reminder_title)->limit(24, '...')." reminder is now overdue";
    
            Notification::send($this->user, new ReminderDeadline($this->user, $this->reminder, 'a reminder is overdue now.', $message, "reminders", $data, null));
        }
    }
}
