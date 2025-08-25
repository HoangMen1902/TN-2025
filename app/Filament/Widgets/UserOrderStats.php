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
    protected int|string|array $columnSpan = 6;

    protected function getStats(): array
    {
        $start = $this->filters['startDate'] ?? null;
        $end = $this->filters['endDate'] ?? null;

        $startDate = $start ? Carbon::parse($start) : null;
        $endDate = $end ? Carbon::parse($end) : now();
        $period = \Carbon\CarbonPeriod::create($startDate, '1 month', $endDate);
        $userChart = [];
        $orderChart = [];
        $revenueChart = [];

        foreach ($period as $date) {
            $from = $date->copy()->startOfMonth();
            $to = $date->copy()->endOfMonth();

            $userChart[] = User::whereBetween('created_at', [$from, $to])->count();
            $orderChart[] = Order::whereBetween('created_at', [$from, $to])->count();
            $revenueChart[] = Order::where('orders_status', 'Đã thanh toán')
                ->whereBetween('created_at', [$from, $to])
                ->sum('total_price');
        }
        return [
            Stat::make('Tổng người dùng', User::whereBetween('created_at', [$startDate, $endDate])->count())
                ->description('Số người dùng mới ')
                ->descriptionIcon('heroicon-m-user-group', \Filament\Support\Enums\IconPosition::Before)
                ->color('success')
                ->chart($userChart),

            Stat::make('Tổng đơn hàng', Order::whereBetween('created_at', [$startDate, $endDate])->count())
                ->description('Tổng số đơn hàng')
                ->descriptionIcon('heroicon-m-shopping-cart', \Filament\Support\Enums\IconPosition::Before)
                ->color('primary')
                ->chart($orderChart),

            Stat::make(
                'Tổng doanh thu',
                number_format(
                    Order::where('orders_status', 'Đã thanh toán')
                        ->whereBetween('created_at', [$startDate, $endDate])
                        ->sum('total_price'),
                    0,
                    ',',
                    '.'
                ) . ' ₫'
            )
                ->description('Tổng doanh thu (đ)')
                ->descriptionIcon('heroicon-m-banknotes', position: \Filament\Support\Enums\IconPosition::Before)
                ->color('warning')
                ->chart($revenueChart),

            Stat::make('SKU sắp hết hàng', ProductSku::where('quantity', '<', 5)->count())
                ->description('Số SKU còn dưới 5 sản phẩm')
                ->descriptionIcon('heroicon-m-exclamation-triangle', \Filament\Support\Enums\IconPosition::Before)
                ->color('danger')
                ->chart([5, 4, 3, 2, 1, 0]),
        ];
    }
}
