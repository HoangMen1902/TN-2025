<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

    protected function afterCreate(): void
    {
        activity()
            ->causedBy(Auth::user())
            ->performedOn($this->record)
            ->log('Tạo mới sản phẩm: ' . $this->record->name);
    }
}
