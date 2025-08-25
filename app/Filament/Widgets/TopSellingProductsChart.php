<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\OrderDetail;
use Carbon\Carbon;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class TopSellingProductsChart extends ChartWidget
{
    use InteractsWithPageFilters;

    protected static ?string $heading = 'Top sản phẩm bán chạy';
    protected int|string|array $columnSpan = 6;

    protected function getData(): array
    {
        $start = $this->filters['startDate'] ?? null;
        $end = $this->filters['endDate'] ?? null;

        $startDate = $start ? Carbon::parse($start) : null;
        $endDate = $end ? Carbon::parse($end) : now();

        $query = OrderDetail::query()
            ->join('orders', 'orders.id', '=', 'order_details.order_id')
            ->join('product_skus', 'product_skus.id', '=', 'order_details.sku_id')
            ->join('products', 'products.id', '=', 'product_skus.product_id')
            ->where('orders.orders_status', 'Đã thanh toán');

        if ($startDate) {
            $query->whereDate('orders.created_at', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('orders.created_at', '<=', $endDate);
        }

        $topProducts = $query
            ->selectRaw('products.name as name, SUM(order_details.quantity) as total')
            ->groupBy('products.name')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Số lượng bán',
                    'data' => $topProducts->pluck('total'),
                ],
            ],
            'labels' => $topProducts->pluck('name'),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
