<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Events\RemindDeadline;
use App\Models\Reminder;
use App\Models\User;
use App\Jobs\NotifyDeadlineReminderJob;
use Carbon\Carbon;

class ReminderDeadlineCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminder:deadline';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $reminders = Reminder::where('due_date', '<=', now())
        ->where('second_notified', false)->get();

        if (!empty($reminders) || count($reminders) > 0) {
            foreach ($reminders as $reminder) {
                $reminder->second_notified = true;
                $user = User::find($reminder->user_id);

                if ($reminder->save()) {
                    event(new RemindDeadline($reminder, $user));
                    
                    if ($user->mail_notification) {
                        NotifyDeadlineReminderJob::dispatch($user, $reminder);
                    }
                }
            }
        }
    }
}
