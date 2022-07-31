<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Events\RemindersSoftDelete;
use App\Models\Reminder;
use App\Models\User;
use Carbon\Carbon;


class SoftDeleteOverdueCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ignoreReminder:softDelete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command is equivalent to ignoring reminders which happens by users themsevels.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $reminders = Reminder::where('due_date', '>=', now()->subMinutes(1))
        ->where('due_date', '<', now())->get();
        
        if (!empty($reminders)) {
            foreach ($reminders as $reminder) {
                event(new RemindersSoftDelete($reminder, User::find($reminder->user_id)));
                $reminder->delete();
            }
        }
    }
}
