<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class cacheImage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:cache-image';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("Bắt đầu cache ảnh...");

        $imageFolder = storage_path('app/public/'); 

        if (!is_dir($imageFolder)) {
            $this->error("Thư mục ảnh không tồn tại: $imageFolder");
            return 1;
        }

        $isWin = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';

        $pythonPath = $isWin
            ? base_path('.venv_clip/Scripts/python.exe')
            : base_path('.venv_clip/bin/python3');

        $scriptPath = base_path('app/Tools/ImgFinder/find.py');

        $cachePath = base_path('app/Tools/ImgFinder/features_cache.pt');

        $cmd = escapeshellcmd("$pythonPath \"$scriptPath\" cache \"$imageFolder\" \"$cachePath\"");

        $this->info("Đang chạy: $cmd");

        $output = shell_exec($cmd . ' 2>&1');

        $this->info("Kết quả:");
        $this->line($output);

        return 0;
    }
}
