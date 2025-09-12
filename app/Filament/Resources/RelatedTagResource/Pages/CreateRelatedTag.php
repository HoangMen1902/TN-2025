<?php

namespace App\Filament\Resources\RelatedTagResource\Pages;

use App\Filament\Resources\RelatedTagResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateRelatedTag extends CreateRecord
{
    protected static string $resource = RelatedTagResource::class;

    protected function afterCreate(): void
{
    activity()
        ->causedBy(Auth::user())
        ->performedOn($this->record)
        ->log('Tạo mới thẻ sản phẩm: ' . $this->record->tag_name);
}
}
