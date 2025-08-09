<?php

namespace Modules\EbookModule\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\EbookAudioJob;
use App\Models\EbookChapter;
use App\Models\ProductEbook;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use setasign\Fpdi\Fpdi;
use Illuminate\Support\Str;
use Smalot\PdfParser\Parser;
use Illuminate\Support\Facades\File;
use App\Jobs\GenerateEbookAudioJob;
use Illuminate\Support\Facades\Artisan;

class PdfSplitController extends Controller
{

    public function processJob($jobId)
{
    $job = EbookAudioJob::findOrFail($jobId);

    // Kiểm tra dữ liệu trước khi dispatch Job
    $chapterIds = json_decode($job->chapter_ids, true);

    if (!is_array($chapterIds) || empty($chapterIds) || !$job->voice) {
        return response()->json(['error' => 'Thiếu dữ liệu job.'], 400);
    }

    // Cập nhật trạng thái sang "processing"
    $job->update(['status' => 'processing']);

    // Gửi job vào queue để xử lý nền
    GenerateEbookAudioJob::dispatch($job->id);

    $artisanPath = base_path('artisan');
    $phpPath = PHP_BINARY;
    exec("$phpPath $artisanPath queue:work --once > /dev/null 2>&1 &");
    
    return response()->json([
        'message' => 'Đang xử lý. Vui lòng kiểm tra sau vài phút.'
    ]);
}
    public function autoSplitByToc($ebookId, $tocPage = 1)
    {
        $ebook = ProductEbook::findOrFail($ebookId);
        $pdfPath = Storage::disk('public')->path($ebook->file_path);

        $text = $this->extractPageText($pdfPath, $tocPage);
        $chapters = $this->parseChaptersFromTocText($text);

        $this->splitPdf($ebookId, $chapters);
    }

    protected function extractPageText($pdfPath, $pageNumber): ?string
    {
        $tempPath = storage_path("app/temp_page.pdf");

        $pdf = new \setasign\Fpdi\Fpdi();
        $pageCount = $pdf->setSourceFile($pdfPath);

        if ($pageNumber < 1 || $pageNumber > $pageCount) {
            throw new \Exception("Trang $pageNumber không tồn tại (PDF có $pageCount trang)");
        }

        $tplId = $pdf->importPage($pageNumber);

        $pdf->AddPage();
        $pdf->useTemplate($tplId);

        $pdf->Output($tempPath, 'F');

        $parser = new \Smalot\PdfParser\Parser();
        try {
            $parsed = $parser->parseFile($tempPath);
            return $parsed->getText();
        } catch (\Exception $e) {
            return null;
        }
    }


    protected function parseChaptersFromTocText($text): array
    {
        $lines = explode("\n", $text);
        $chapters = []; 
        foreach ($lines as $line) {
            if (preg_match('/(Chương\s+\d+)[\s\p{Z}\.]*?(\d+)/u', trim($line), $matches)) {
                $chapters[] = [
                    'chapter_name' => $matches[1],
                    'start_page' => (int)$matches[2],
                ];
            }
        }
        for ($i = 0; $i < count($chapters) - 1; $i++) {
            $chapters[$i]['end_page'] = $chapters[$i + 1]['start_page'] - 1;
        }  
        foreach ($chapters as $index => &$chapter) {
            $chapter['is_locked'] = $index === 0 ? 0 : 1;
        }
        return $chapters;
    }
    public function splitPdf($ebookId, array $chapters = [])
    {
        // Tìm ebook theo ID
        $ebook = ProductEbook::findOrFail($ebookId);
        $filePath = $ebook->file_path; // Ví dụ: ebooks/01JXK1VNHPA0ZRZ2W4K7RJ2T2N.pdf
        $pdfPath = Storage::disk('public')->path($filePath); // Đường dẫn: storage/app/public/ebooks/
        $outputDir = Storage::disk('public')->path('ebooks/chapters/');

        // Kiểm tra file PDF tồn tại
        if (!Storage::disk('public')->exists($filePath)) {
            return redirect()->back()->withErrors(['error' => 'File PDF không tồn tại tại đường dẫn: ' . $filePath]);
        }

        // Đảm bảo thư mục đầu ra tồn tại
        if (!file_exists($outputDir)) {
            mkdir($outputDir, 0755, true);
        }

        // Nếu không có chapters từ form, báo lỗi hoặc sử dụng logic mặc định
        if (empty($chapters)) {
            return redirect()->back()->withErrors(['error' => 'Vui lòng cung cấp thông tin các chương để tách.']);
        }

        // Khởi tạo FPDI
        $pdf = new Fpdi();
        $pageCount = $pdf->setSourceFile($pdfPath);


        foreach ($chapters as $chapter) {
            if (
                !isset($chapter['start_page']) ||
                !isset($chapter['end_page']) ||
                $chapter['start_page'] < 1 ||
                $chapter['end_page'] > $pageCount ||
                $chapter['start_page'] > $chapter['end_page']
            ) {
                continue;
            }

            $newPdf = new Fpdi();
            for ($pageNo = $chapter['start_page']; $pageNo <= $chapter['end_page']; $pageNo++) {
                $newPdf->AddPage();
                $newPdf->setSourceFile($pdfPath);
                $tplId = $newPdf->importPage($pageNo);
                $newPdf->useTemplate($tplId);
            }

            // Tạo đường dẫn file chương
            $chapterPath = "ebooks/chapters/{$ebookId}_" . Str::slug($chapter['chapter_name']) . ".pdf";
            $chapterFullPath = Storage::disk('public')->path($chapterPath);
            $newPdf->Output($chapterFullPath, 'F');

            // 🧠 Đọc nội dung từ file chương
            $parser = new Parser();
            $pdfText = null;
            try {
                $pdf = $parser->parseFile($chapterFullPath);
                $pdfText = $pdf->getText(); // Trả về nội dung dạng chuỗi
            } catch (\Exception $e) {
                $pdfText = null; // fallback nếu lỗi
            }

            // Lưu vào database
            $ebook->chapters()->create([
                'chapter_name' => $chapter['chapter_name'],
                'file_path' => $chapterPath,
                'start_page' => $chapter['start_page'],
                'end_page' => $chapter['end_page'],
                'content' => $pdfText,
                'is_locked' => isset($chapter['is_locked']) ? (bool)$chapter['is_locked'] : false,
            ]);
        }


        return redirect()->back()->with('success', 'PDF đã được tách thành các chương!');
    }
}
