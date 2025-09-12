<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Enums\TabsLayout;
use Filament\Resources\Components\Tab;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;
    public function getTitle(): string
    {
        return 'Đơn hàng đã duyệt';
    }

    public function getFilteredTableQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getFilteredTableQuery()
            ->orderBy('is_approved', 'asc')
            ->orderBy('created_at', 'desc');
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
    public function getTabs(): array
    {
        return [
            'all' => Tab::make('Tất cả'),
            'approved' => Tab::make('Đã duyệt')
                ->modifyQueryUsing(fn($query) => $query->where('is_approved', true)),
            'not_approved' => Tab::make('Chưa duyệt')
                ->modifyQueryUsing(fn($query) => $query->where('is_approved', false)),
        ];
    }
}
