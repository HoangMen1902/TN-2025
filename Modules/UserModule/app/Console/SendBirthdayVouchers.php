<?php

namespace Modules\UserModule\Console;

use App\Models\User;
use App\Models\Voucher;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Support\Facades\Log;

class SendBirthdayVouchers extends Command
{

    protected $signature = 'user:send-birthday-vouchers';

    protected $description = 'Gửi voucher sinh nhật cho người dùng hôm nay';

    public function handle()
    {
        $today = now()->format('m-d');

        $users = User::whereRaw("DATE_FORMAT(birthday, '%m-%d') = ?", [$today])->get();

        $voucher = Voucher::where('id', 1)->first();
   
        $notification = DB::table('notifications')
            ->where('notification_type', 'Chúc mừng sinh nhật')
            ->whereNull('deleted_at')
            ->first();
  
            // Log::info('Notification:', (array) $notification); 
        foreach ($users as $user) {
            $alreadyReceived = DB::table('voucher_used')
                ->where('user_id', $user->id)
                ->where('voucher_id', $voucher->id)
                ->exists();

            if (!$alreadyReceived) {
                DB::table('voucher_used')->insert([
                    'user_id' => $user->id,
                    'voucher_id' => $voucher->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                // Gửi thông báo sinh nhật
                if ($notification) {
                    DB::table('user_notifications')->insert([
                        'user_id' => $user->id,
                        'notification_id' => $notification->id,
                        'notification_status' => 'unread',
                    ]);
                }
            }
        }

        Log::info('Đã gửi voucher và thông báo sinh nhật cho người dùng!');
    }






    public function __construct()
    {
        parent::__construct();
    }


    /**
     * Get the console command arguments.
     */
    protected function getArguments(): array
    {
        return [
            ['example', InputArgument::REQUIRED, 'An example argument.'],
        ];
    }

    /**
     * Get the console command options.
     */
    protected function getOptions(): array
    {
        return [
            ['example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null],
        ];
    }
}
