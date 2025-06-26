<?php

namespace App\Filament\Resources\UserResource\Widgets;

use App\Models\OrderDetail;
use App\Models\ProductSku;
use Filament\Widgets\ChartWidget;

class TopOrderedProductsChart extends ChartWidget
{
    public $record;

    protected static ?string $heading = 'Top sản phẩm đã mua';

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getData(): array
    {
        $user = $this->record ?? null;
        if (!$user) return ['datasets' => [], 'labels' => []];

        $skuIds = OrderDetail::whereHas('order', fn($q) => $q->where('user_id', $user->id))
            ->pluck('sku_id');
        $data = ProductSku::whereIn('id', $skuIds)
            ->with('product')
            ->get()
            ->groupBy('product_id')
            ->map(function ($items) {
                $product = $items->first()->product;
                return [
                    'name' => $product ? $product->name : 'N/A',
                    'quantity' => $items->count(),
                ];
            })
            ->sortByDesc('quantity')
            ->take(5)
            ->values();

        return [
            'datasets' => [
                [
                    'label' => 'Số lần mua',
                    'data' => $data->pluck('quantity'),
                ],
            ],
            'labels' => $data->pluck('name')->map(fn($v) => strlen($v) > 15 ? mb_substr($v, 0, 15) . '...' : $v),
        ];
    }
}