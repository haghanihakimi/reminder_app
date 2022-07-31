<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reminder;
use Carbon\Carbon;

class DeleteOverdueCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deleteReminder:forceDelete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command is equivalent to deleting reminder by users themselves.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        return Reminder::withTrashed()->where('due_date', '<=', now()->subDays(7))->forceDelete();
    }
}
