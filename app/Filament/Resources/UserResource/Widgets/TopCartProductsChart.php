<?php

namespace App\Filament\Resources\UserResource\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\User;

class TopCartProductsChart extends ChartWidget
{
    public $record;

    protected static ?string $heading = 'Top sản phẩm trong giỏ hàng';

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getData(): array
    {
        $user = $this->record ?? null;
        if (!$user) return ['datasets' => [], 'labels' => []];

        $data = $user->cartItems()
            ->with('sku.product')
            ->get()
            ->groupBy('sku_id')
            ->map(function ($items) {
                $sku = $items->first()->sku;
                return [
                    'name' => $sku && $sku->product ? $sku->product->name : 'N/A',
                    'quantity' => $items->sum('quantity'),
                ];
            })
            ->sortByDesc('quantity')
            ->take(5)
            ->values();

        return [
            'datasets' => [
                [
                    'label' => 'Số lượng trong giỏ',
                    'data' => $data->pluck('quantity'),
                ],
            ],
            'labels' => $data->pluck('name')->map(fn($v) => strlen($v) > 15 ? mb_substr($v, 0, 15) . '...' : $v),
        ];
    }
}