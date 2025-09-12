<?php

namespace App\Filament\Resources\VoucherResource\Pages;

use App\Filament\Resources\VoucherResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
class CreateVoucher extends CreateRecord
{
    protected static string $resource = VoucherResource::class;

    protected function afterCreate(): void
{
    activity()
        ->causedBy(Auth::user())
        ->performedOn($this->record)
        ->log('Tạo mới nhà xuất bản: ' . $this->record->publisher_name);
}
}
