<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Order;
use Carbon\Carbon;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class OrderStatusChart extends ChartWidget
{
    use InteractsWithPageFilters;
    protected static ?string $heading = 'Đơn hàng theo trạng thái';

    protected function getData(): array
    {
        $start = $this->filters['startDate'] ?? null;
        $end = $this->filters['endDate'] ?? null;

        $startDate = $start ? Carbon::parse($start) : null;
        $endDate = $end ? Carbon::parse($end) : now();

        $statuses = [
            'Đang xử lý',
            'Chờ thanh toán',
            'Đã thanh toán',
            'Đã giao',
            'Đã hủy'
        ];
        $counts = [];
        foreach ($statuses as $status) {
            $query = Order::where('orders_status', $status);
            if ($startDate) {
                $query->whereDate('created_at', '>=', $startDate);
            }
            if ($endDate) {
                $query->whereDate('created_at', '<=', $endDate);
            }
            $counts[] = $query->count();
        }
        return [
            'datasets' => [
                [
                    'label' => 'Số lượng đơn',
                    'data' => $counts,
                ],
            ],
            'labels' => $statuses,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
