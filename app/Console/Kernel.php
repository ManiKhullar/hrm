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
        // $schedule->command('reminder:timesheet')->everyMinute();
        // $schedule->call('App\Http\Controllers\ReminderTimeSheetController@timesheet_reminder')->weeklyOn(2, 4, '21:00');
        $schedule->call('App\Http\Controllers\LeaveController@leave_count')->daily();

    }
 
    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
