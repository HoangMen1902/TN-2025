<?php

namespace   Modules\EbookModule\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ProductEbook;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ZipArchive;
use SimpleXMLElement;
use Illuminate\Support\Facades\Log;

class EpubSplitController extends Controller
{
    public function splitEpub($ebookId)
    {
        // Tìm ebook theo ID
        $ebook = ProductEbook::findOrFail($ebookId);
        $filePath = $ebook->file_path; // Ví dụ: ebooks/test.epub
        $epubPath = Storage::disk('public')->path($filePath);
        $outputDir = Storage::disk('public')->path('ebooks/chapters/');

        Log::info('Bắt đầu tách ePub', ['ebook_id' => $ebookId, 'file_path' => $filePath]);

        // Kiểm tra file ePub tồn tại
        if (!Storage::disk('public')->exists($filePath)) {
            Log::error('File ePub không tồn tại', ['file_path' => $filePath]);
            return redirect()->back()->withErrors(['error' => 'File ePub không tồn tại tại đường dẫn: ' . $filePath]);
        }

        // Đảm bảo thư mục đầu ra tồn tại
        if (!file_exists($outputDir)) {
            mkdir($outputDir, 0755, true);
        }

        // Giải nén file ePub
        $zip = new ZipArchive();
        if ($zip->open($epubPath) !== true) {
            Log::error('Không thể mở file ePub', ['file_path' => $epubPath]);
            return redirect()->back()->withErrors(['error' => 'Không thể mở file ePub.']);
        }

        // Tạo thư mục tạm để giải nén
        $tempDir = storage_path('app/public/ebooks/temp_' . $ebookId);
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0755, true);
        }
        $zip->extractTo($tempDir);
        $zip->close();
        Log::info('Đã giải nén ePub vào thư mục tạm', ['temp_dir' => $tempDir]);

        // Tìm file toc.ncx hoặc content.opf
        $chapters = [];
        $possiblePaths = [
            'OEBPS/toc.ncx',
            'EPUB/toc.ncx',
            'toc.ncx',
            'OEBPS/content.opf',
            'EPUB/content.opf',
            'content.opf',
        ];

        $tocPath = null;
        $opfPath = null;
        foreach ($possiblePaths as $path) {
            if (file_exists($tempDir . '/' . $path)) {
                if (str_contains($path, 'toc.ncx')) {
                    $tocPath = $tempDir . '/' . $path;
                } elseif (str_contains($path, 'content.opf')) {
                    $opfPath = $tempDir . '/' . $path;
                }
            }
        }

        if ($tocPath) {
            // Đọc toc.ncx
            try {
                $tocXml = new SimpleXMLElement(file_get_contents($tocPath));
                foreach ($tocXml->navMap->navPoint as $navPoint) {
                    $chapterName = (string) $navPoint->navLabel->text;
                    $chapterFile = dirname($tocPath, 1) . '/' . (string) $navPoint->content['src'];
                    $chapterFile = str_replace($tempDir . '/', '', $chapterFile);
                    $chapters[] = [
                        'chapter_name' => $chapterName ?: 'Chương ' . (count($chapters) + 1),
                        'file_path' => $chapterFile,
                    ];
                }
                Log::info('Đã tìm thấy chương từ toc.ncx', ['chapters' => $chapters]);
            } catch (\Exception $e) {
                Log::error('Lỗi khi đọc toc.ncx: ' . $e->getMessage(), ['toc_path' => $tocPath]);
            }
        } elseif ($opfPath) {
            // Đọc content.opf
            try {
                $opfXml = new SimpleXMLElement(file_get_contents($opfPath));
                $index = 1;
                foreach ($opfXml->manifest->item as $item) {
                    if (strpos($item['media-type'], 'application/xhtml+xml') !== false) {
                        $chapterFile = dirname($opfPath, 1) . '/' . (string) $item['href'];
                        $chapterFile = str_replace($tempDir . '/', '', $chapterFile);
                        $chapters[] = [
                            'chapter_name' => 'Chương ' . $index,
                            'file_path' => $chapterFile,
                        ];
                        $index++;
                    }
                }
                Log::info('Đã tìm thấy chương từ content.opf', ['chapters' => $chapters]);
            } catch (\Exception $e) {
                Log::error('Lỗi khi đọc content.opf: ' . $e->getMessage(), ['opf_path' => $opfPath]);
            }
        } else {
            // Không tìm thấy toc.ncx hoặc content.opf
            Log::error('File ePub không có cấu trúc chuẩn', ['temp_dir' => $tempDir]);
            Storage::disk('public')->deleteDirectory('ebooks/temp_' . $ebookId);
            return redirect()->back()->withErrors(['error' => 'File ePub không có cấu trúc chuẩn (thiếu toc.ncx hoặc content.opf).']);
        }

        // Lưu các chương vào thư mục và cơ sở dữ liệu
        if (empty($chapters)) {
            Log::error('Không tìm thấy chương nào trong file ePub', ['ebook_id' => $ebookId]);
            Storage::disk('public')->deleteDirectory('ebooks/temp_' . $ebookId);
            return redirect()->back()->withErrors(['error' => 'Không tìm thấy chương nào trong file ePub.']);
        }

        foreach ($chapters as $chapter) {
            $sourceFile = $tempDir . '/' . $chapter['file_path'];
            if (file_exists($sourceFile)) {
                $chapterPath = "ebooks/chapters/{$ebookId}_" . Str::slug($chapter['chapter_name']) . ".html";
                Storage::disk('public')->copy($chapter['file_path'], $chapterPath);
                Log::info('Đã sao chép chương', ['source' => $chapter['file_path'], 'destination' => $chapterPath]);

                // Lưu vào bảng ebook_chapters
                $ebook->chapters()->create([
                    'chapter_name' => $chapter['chapter_name'],
                    'file_path' => $chapterPath,
                    'start_page' => null,
                    'end_page' => null,
                ]);
            } else {
                Log::warning('File chương không tồn tại', ['source_file' => $sourceFile]);
            }
        }

        // Xóa thư mục tạm
        Storage::disk('public')->deleteDirectory('ebooks/temp_' . $ebookId);
        Log::info('Đã xóa thư mục tạm', ['temp_dir' => $tempDir]);

        return redirect()->back()->with('success', 'ePub đã được tách thành các chương!');
    }
}