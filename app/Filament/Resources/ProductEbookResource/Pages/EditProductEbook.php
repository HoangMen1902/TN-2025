<?php

namespace App\Filament\Resources\ProductEbookResource\Pages;

use App\Filament\Resources\ProductEbookResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProductEbook extends EditRecord
{
    protected static string $resource = ProductEbookResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
