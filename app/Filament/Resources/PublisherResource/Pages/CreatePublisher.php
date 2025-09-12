<?php

namespace App\Filament\Resources\PublisherResource\Pages;

use App\Filament\Resources\PublisherResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreatePublisher extends CreateRecord
{
    protected static string $resource = PublisherResource::class;

      protected function afterCreate(): void
{
    activity()
        ->causedBy(Auth::user())
        ->performedOn($this->record)
        ->log('Tạo mới nhà xuất bản: ' . $this->record->publisher_name);
}
}
