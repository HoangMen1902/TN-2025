<?php

namespace App\Filament\Resources\RelatedTagResource\Pages;

use App\Filament\Resources\RelatedTagResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;

class EditRelatedTag extends EditRecord
{
    protected static string $resource = RelatedTagResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        activity()
            ->causedBy(Auth::user())
            ->performedOn($this->record)
            ->log('Cập nhật thẻ sản phẩm: ' . $this->record->tag_name);
    }
}
