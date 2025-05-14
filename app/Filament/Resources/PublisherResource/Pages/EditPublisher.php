<?php

namespace App\Filament\Resources\PublisherResource\Pages;

use App\Filament\Resources\PublisherResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

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
}
