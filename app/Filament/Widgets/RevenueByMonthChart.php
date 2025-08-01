<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Order;
use Carbon\Carbon;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class RevenueByMonthChart extends ChartWidget
{
    use InteractsWithPageFilters;

    protected static ?string $heading = 'Doanh thu ';

    protected function getData(): array
    {
        $start = $this->filters['startDate'] ?? null;
        $end = $this->filters['endDate'] ?? null;

        $startDate = $start ? Carbon::parse($start) : null;
        $endDate = $end ? Carbon::parse($end) : now();

        $labels = [];
        $data = [];

        for ($i = 1; $i <= 12; $i++) {
            $query = Order::whereMonth('created_at', $i)
                ->whereYear('created_at', now()->year)
                ->where('orders_status', 'Đã giao');

            if ($startDate) {
                $query->whereDate('created_at', '>=', $startDate);
            }
            if ($endDate) {
                $query->whereDate('created_at', '<=', $endDate);
            }

            $labels[] = "Tháng $i";

            $data[] = $query->get()->sum(function ($order) {
                return ($order->total_price ?? 0) - ($order->shipment_price ?? 0);
            });
        }

        return [
            'datasets' => [
                [
                    'label' => 'Doanh thu (đ)', 
                    'data' => $data,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
