<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Enviar recordatorios de citas 3 días antes a las 09:00
        $schedule->command('appointments:send-reminders', ['days' => 3])
            ->dailyAt('09:00')
            ->description('Enviar recordatorios de citas 3 días antes');

        // Enviar recordatorios 1 día antes a las 14:00 (2 PM)
        $schedule->command('appointments:send-reminders', ['days' => 1])
            ->dailyAt('14:00')
            ->description('Enviar recordatorios de citas 1 día antes');
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
