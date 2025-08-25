<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class TopRatedProductsChart extends ChartWidget
{
    use InteractsWithPageFilters;
    protected int|string|array $columnSpan = 6;

    protected static ?string $heading = 'Top sản phẩm được đánh giá cao nhất';
    protected function getData(): array
    {
        $start = $this->filters['startDate'] ?? null;
        $end = $this->filters['endDate'] ?? null;

        $startDate = $start ? Carbon::parse($start) : null;
        $endDate = $end ? Carbon::parse($end) : now();

        $topProducts = DB::table('ratings')
            ->join('order_details', 'ratings.order_detail_id', '=', 'order_details.id')
            ->join('product_skus', 'order_details.sku_id', '=', 'product_skus.id')
            ->join('products', 'product_skus.product_id', '=', 'products.id')
            ->when($startDate, fn($q) => $q->whereDate('ratings.created_at', '>=', $startDate))
            ->when($endDate, fn($q) => $q->whereDate('ratings.created_at', '<=', $endDate))
            ->select('products.id', 'products.name')
            ->selectRaw('AVG(ratings.rating) as avg_rating')
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('avg_rating')
            ->limit(10)
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Điểm trung bình',
                    'data' => $topProducts->pluck('avg_rating'),
                ],
            ],
            'labels' => $topProducts->map(function ($item) {
                return 'ID: ' . $item->id;
            }),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
