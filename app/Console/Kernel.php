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
        // Exécute la commande toutes les heures
        $schedule->command('threat-intel:get --days=1 --tenant_id=1') // Ajustez tenant_id si nécessaire
                 ->hourly()
                 ->withoutOverlapping(); // Empêche l'exécution de multiples instances en même temps
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