<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Modules\UserModule\Console\SendBirthdayVouchers;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        SendBirthdayVouchers::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        $schedule->command('user:send-birthday-vouchers')->dailyAt('06:00');
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
