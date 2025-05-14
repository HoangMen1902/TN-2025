<?php

namespace App\Filament\Resources\RelatedTagResource\Pages;

use App\Filament\Resources\RelatedTagResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRelatedTag extends EditRecord
{
    protected static string $resource = RelatedTagResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
