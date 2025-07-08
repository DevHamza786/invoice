<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use App\Console\Commands\RecurringInvoiceCommand;
use App\Console\Commands\DueDateInvoiceReminderCommand;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  Schedule  $schedule
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command(RecurringInvoiceCommand::class)
            ->daily()
            ->appendOutputTo(storage_path('logs/commands.log'));

        $schedule->command(DueDateInvoiceReminderCommand::class)
            ->daily()
            ->appendOutputTo(storage_path('logs/commands.log'));
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
