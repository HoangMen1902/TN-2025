<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ViettelPostService;

class UpdateViettelPostToken extends Command
{
    protected $signature = 'viettelpost:update-token';
    protected $description = 'Update Viettel Post token';

    public function handle()
    {
        $viettelService = new ViettelPostService();
        if ($viettelService->updateToken()) {
            $this->info('Cập nhật token Viettel Post thành công!');
        } else {
            $this->error('Cập nhật token Viettel Post thất bại!');
        }
    }
}