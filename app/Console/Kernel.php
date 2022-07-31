<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        //$schedule->command('queue:retry all')->everyMinute();
        $schedule->command('ignoreReminder:softDelete')->everyMinute()->withoutOverlapping(1);
        $schedule->command('deleteReminder:forceDelete')->everyMinute()->withoutOverlapping(1);
        $schedule->command('reminder:closeDeadline')->everyMinute()->withoutOverlapping(1);
        $schedule->command('reminder:deadline')->everyMinute()->withoutOverlapping(1);
        $schedule->command('model:prune')->everyMinute()->withoutOverlapping(1);
        //$schedule->command('queue:work')->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
