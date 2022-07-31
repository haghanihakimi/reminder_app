<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Events\RemindCloseDeadline;
use App\Models\Reminder;
use App\Models\User;
use App\Jobs\NotifyCloseDeadlineJob;
use Carbon\Carbon;

class ReminderkCloseDeadlineCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminder:closeDeadline';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command checks if reminder is 3 days close to deadline.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $reminders = Reminder::where('due_date', '>=', now()->addDays(2))
        ->where('due_date', '<=', now()->addDays(3))
        ->where('first_notified', false)->get();

        foreach ($reminders as $reminder) {
            $reminder->first_notified = true;
            $user = User::find($reminder->user_id);
            
            if ($reminder->save()){ 
                event(new RemindCloseDeadline($reminder, $user));
                if ($user->mail_notification) {
                    NotifyCloseDeadlineJob::dispatch($user, $reminder);
                }
            }
        }
    }
}
