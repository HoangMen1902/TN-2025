<?php

namespace Modules\EbookModule\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ProductEbook;
use Illuminate\Support\Facades\Storage;
use setasign\Fpdi\Fpdi;
use Illuminate\Support\Str;
use Smalot\PdfParser\Parser;

class PdfSplitController extends Controller
{
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

        // foreach ($chapters as $chapter) {
        //     // Kiểm tra phạm vi trang hợp lệ
        //     if (
        //         !isset($chapter['start_page']) ||
        //         !isset($chapter['end_page']) ||
        //         $chapter['start_page'] < 1 ||
        //         $chapter['end_page'] > $pageCount ||
        //         $chapter['start_page'] > $chapter['end_page']
        //     ) {
        //         continue; // Bỏ qua nếu phạm vi không hợp lệ
        //     }

        //     $newPdf = new Fpdi();
        //     for ($pageNo = $chapter['start_page']; $pageNo <= $chapter['end_page']; $pageNo++) {
        //         $newPdf->AddPage();
        //         $newPdf->setSourceFile($pdfPath);
        //         $tplId = $newPdf->importPage($pageNo);
        //         $newPdf->useTemplate($tplId);
        //     }

        //     // Lưu file chương
        //     $chapterPath = "ebooks/chapters/{$ebookId}_" . Str::slug($chapter['chapter_name']) . ".pdf";
        //     $newPdf->Output(Storage::disk('public')->path($chapterPath), 'F');

        //     // Lưu thông tin chương vào cơ sở dữ liệu
        //     $ebook->chapters()->create([
        //         'chapter_name' => $chapter['chapter_name'],
        //         'file_path' => $chapterPath,
        //         'start_page' => $chapter['start_page'],
        //         'end_page' => $chapter['end_page'],
        //     ]);
        // }

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
