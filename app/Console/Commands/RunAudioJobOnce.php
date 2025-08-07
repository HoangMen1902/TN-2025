<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use App\Models\EbookAudioJob;
use App\Jobs\GenerateEbookAudioJob;

class RunAudioJobOnce extends Command
{
    protected $signature = 'audio:generate {jobId}';
    protected $description = 'Dispatch và xử lý job tạo audio chỉ 1 lần duy nhất';

    public function handle()
    {
        $jobId = $this->argument('jobId');

        $job = EbookAudioJob::find($jobId);
        if (!$job) {
            Log::error("Không tìm thấy job với ID: {$jobId}");
            return;
        }
        GenerateEbookAudioJob::dispatch($jobId);
        Artisan::call('queue:work', ['--once' => true]);
    }
}
