<?php

namespace App\Filament\Resources\PublisherResource\Pages;

use App\Filament\Resources\PublisherResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;

class EditPublisher extends EditRecord
{
    protected static string $resource = PublisherResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\DeleteAction::make()->label('Xóa'),
            \Filament\Actions\ForceDeleteAction::make()->label('Xóa vĩnh viễn'),
            \Filament\Actions\RestoreAction::make()->label('Khôi phục'),
        ];
    }
    protected function afterSave(): void
    {
        activity()
            ->causedBy(Auth::user())
            ->performedOn($this->record)
            ->log('Cập nhật nhà xuất bản: ' . $this->record->publisher_name);
    }
}
