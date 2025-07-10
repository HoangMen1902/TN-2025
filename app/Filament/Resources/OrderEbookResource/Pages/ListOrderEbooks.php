<?php

namespace App\Filament\Resources\OrderEbookResource\Pages;

use App\Filament\Resources\OrderEbookResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListOrderEbooks extends ListRecords
{
    protected static string $resource = OrderEbookResource::class;

    public function getTitle(): string
    {
        return 'Đơn hàng Ebook';
    }

    public function getFilteredTableQuery(): Builder
    {
        return parent::getFilteredTableQuery()
            ->orderBy('created_at', 'desc');
    }

    protected function getHeaderActions(): array
    {
        return [
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['user', 'paymentDetail']);
    }
}