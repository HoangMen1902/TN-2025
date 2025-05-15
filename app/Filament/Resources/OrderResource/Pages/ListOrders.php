<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;
    public function getTitle(): string
    {
        return 'Đơn hàng đã duyệt';
    }

    public function getFilteredTableQuery(): Builder
    {
        return parent::getFilteredTableQuery()
            ->orderBy('is_approved', 'asc')
            ->orderBy('created_at', 'asc');
    }


    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()
            ->with('orderDetails.productSku.product');
    }
}
