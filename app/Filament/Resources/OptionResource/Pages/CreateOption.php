<?php

namespace App\Filament\Resources\OptionResource\Pages;

use App\Filament\Resources\OptionResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateOption extends CreateRecord
{
    protected static string $resource = OptionResource::class;

    protected function afterCreate(): void
    {
        activity()
            ->causedBy(Auth::user())
            ->performedOn($this->record)
            ->log('Tạo mới thuộc tính: ' . $this->record->name);
    }
}
