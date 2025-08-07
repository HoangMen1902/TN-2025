<?php

namespace App\Jobs;

use App\Models\EbookChapter;
use App\Models\EbookAudioJob;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateEbookAudioJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 0;

    protected $jobId;

    public function __construct($jobId)
    {
        $this->jobId = $jobId;
    }

    public function handle()
    {
        Log::info(" Bắt đầu xử lý công việc tạo audio cho job ID: {$this->jobId}");

        $job = EbookAudioJob::find($this->jobId);
        if (!$job || !$job->chapter_ids) {
            Log::error("Không tìm thấy job hoặc thiếu chapter_ids cho job ID: {$this->jobId}");
            return;
        }

        $chapterIds = json_decode($job->chapter_ids, true);
        Log::info(" Danh sách chapter ID: ", $chapterIds);

        $chapters = EbookChapter::whereIn('id', $chapterIds)->get();

        if ($chapters->isEmpty()) {
            Log::error("Không tìm thấy chương nào tương ứng với chapter_ids.");
            $job->update(['status' => 'failed']);
            return;
        }

        $audioFiles = [];

        foreach ($chapters as $chapterIndex => $chapter) {
            $chapterText = $chapter->content;
            Log::info("Bắt đầu xử lý chương '{$chapter->chapter_name}' với độ dài: " . strlen($chapterText));

            $chunks = str_split($chapterText, 3000);
            Log::info("Chia chương thành " . count($chunks) . " đoạn nhỏ");

            foreach ($chunks as $chunkIndex => $text) {
                Log::info("Gửi đoạn {$chunkIndex} của chương '{$chapter->chapter_name}' đến API FPT.AI");
                Log::info("Nội dung đoạn {$chunkIndex}: " . $text);
 
                $response = Http::withHeaders([
                    'api-key' => env('FPT_AI_API_KEY'),
                    'speed' => $job->speed ?? '0',
                    'voice' => $job->voice,
                    ])->withBody($text, 'text/plain')->post(env('FPT_AI_API_URL'));

                Log::info("Phản hồi từ API:", $response->json());

                if (!$response->successful() || !isset($response['async'])) {
                    Log::error("API trả về lỗi hoặc thiếu async URL", $response->json());
                    $job->update(['status' => 'failed']);
                    return;
                }

                $audioUrl = $response['async'];
                Log::info(" Chờ tải audio từ: {$audioUrl}");

                $audioContent = null;
                for ($i = 1; $i <= 240; $i++) {
                    $audioContent = @file_get_contents($audioUrl);
                    if ($audioContent) {
                        Log::info("Tải thành công sau {$i} lần thử");
                        break;
                    }
                    Log::info(" Lần thử {$i}/240 - chờ 15s...");
                    sleep(15);
                }

                if (!$audioContent) {
                    Log::error("Không thể tải file từ {$audioUrl} sau 60 phút.");
                    $job->update(['status' => 'failed']);
                    return;
                }

                $filename = "ebooks/audio/{$job->id}_chapter{$chapter->id}_part{$chunkIndex}.mp3";
                Storage::disk('public')->put($filename, $audioContent);
                $audioFiles[] = storage_path("app/public/" . $filename);

                Log::info("Đã lưu file: {$filename}");
            }
        }

        Log::info("Bắt đầu gộp file bằng ffmpeg");

        $combinedFile = storage_path("app/public/ebooks/audio/{$job->id}_combined.mp3");
        $listFilePath = storage_path("app/public/ebooks/audio/{$job->id}_list.txt");

        $listContent = collect($audioFiles)->map(function ($file) {
            return "file '" . str_replace("'", "'\\''", $file) . "'";
        })->implode("\n");

        file_put_contents($listFilePath, $listContent);

        $cmd = "ffmpeg -f concat -safe 0 -i \"$listFilePath\" -c copy \"$combinedFile\"";
        Log::info(" Chạy lệnh ffmpeg: $cmd");
        exec($cmd);

        Log::info("Đã gộp file tại: {$combinedFile}");

        $job->update([
            'output_path' => 'storage/ebooks/audio/' . $job->id . '_combined.mp3',
            'status' => 'completed',
        ]);

        Log::info("Hoàn thành công việc tạo audio cho job ID: {$this->jobId}");
    }
}
