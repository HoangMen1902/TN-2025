<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\User;
use App\Models\Order;
use App\Models\ProductSku;
use Carbon\Carbon;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class UserOrderStats extends BaseWidget
{
    use InteractsWithPageFilters;

    protected function getStats(): array
    {
        $start = $this->filters['startDate'] ?? null;
        $end = $this->filters['endDate'] ?? null;

        $startDate = $start ? Carbon::parse($start) : null;
        $endDate = $end ? Carbon::parse($end) : now();

        return [
            Stat::make('Tổng người dùng', User::whereBetween('created_at', [$startDate, $endDate])->count())
                ->description('Số người dùng mới ')
                ->descriptionIcon('heroicon-m-user-group', \Filament\Support\Enums\IconPosition::Before)
                ->color('success')
                ->chart([2, 4, 6, 8, 10, 12]),

            Stat::make('Tổng đơn hàng', Order::whereBetween('created_at', [$startDate, $endDate])->count())
                ->description('Tổng số đơn hàng')
                ->descriptionIcon('heroicon-m-shopping-cart', \Filament\Support\Enums\IconPosition::Before)
                ->color('primary')
                ->chart([3, 5, 7, 9, 11, 13]),

            Stat::make('Tổng doanh thu', Order::where('orders_status', 'Đã thanh toán')->whereBetween('created_at', [$startDate, $endDate])->sum('total_price'))
                ->description('Tổng doanh thu (đ)')
                ->descriptionIcon('heroicon-m-banknotes', \Filament\Support\Enums\IconPosition::Before)
                ->color('warning')
                ->chart([100, 200, 300, 400, 500, 600]),

            Stat::make('SKU sắp hết hàng', ProductSku::where('quantity', '<', 5)->count())
                ->description('Số SKU còn dưới 5 sản phẩm')
                ->descriptionIcon('heroicon-m-exclamation-triangle', \Filament\Support\Enums\IconPosition::Before)
                ->color('danger')
                ->chart([5, 4, 3, 2, 1, 0]),
        ];
    }
}
