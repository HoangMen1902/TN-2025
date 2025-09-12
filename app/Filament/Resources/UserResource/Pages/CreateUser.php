<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

      protected function afterCreate(): void
    {
        activity()
            ->causedBy(Auth::user())
            ->performedOn($this->record)
            ->log('Tạo mới người dùng: ' . $this->record->name);
    }
}
