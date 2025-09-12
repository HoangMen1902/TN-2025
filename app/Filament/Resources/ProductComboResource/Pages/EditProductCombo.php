<?php

namespace App\Filament\Resources\ProductComboResource\Pages;

use App\Filament\Resources\ProductComboResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;

class EditProductCombo extends EditRecord
{
    protected static string $resource = ProductComboResource::class;

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
            ->log('Cập nhật combo sản phẩm: ' . $this->record->name);
    }
}
