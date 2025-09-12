<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Modules\UserModule\Console\SendBirthdayVouchers;
use App\Console\Commands\CreateSuperAdmin;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        SendBirthdayVouchers::class,
        CreateSuperAdmin::class,
        \Modules\DetailModule\Console\SendPreorderNotifications::class,
    ];


    protected function schedule(Schedule $schedule)
    {
        $schedule->command('user:send-birthday-vouchers')->dailyAt('06:00');
        $schedule->command('preorder:notify')->everyMinute();
    }

    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
