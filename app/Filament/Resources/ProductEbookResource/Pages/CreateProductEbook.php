<?php

namespace App\Filament\Resources\ProductEbookResource\Pages;

use App\Filament\Resources\ProductEbookResource;
use App\Models\ProductEbook;
use Filament\Resources\Pages\CreateRecord;
use Smalot\PdfParser\Parser;
use Illuminate\Support\Facades\Storage;

class CreateProductEbook extends CreateRecord
{
    protected static string $resource = ProductEbookResource::class;

    protected function afterCreate(): void
    {
        $record = $this->record;
    
        if ($record->filepath) {
            $pdfPath = Storage::disk('public')->path($record->filepath);
    
            if (file_exists($pdfPath)) {
                $parser = new Parser();
                $pdf = $parser->parseFile($pdfPath);
                $text = $pdf->getText();
    
                $record->update([
                    'content' => $text,
                ]);
    
                // Kiểm tra sau khi lưu
                // dd('Đã lưu xong:', $record->content);
            } else {
                // dd('Không tìm thấy file:', $pdfPath);
            }
        } else {
            // dd('Không có filepath trong record');
        }
    }
    
}
