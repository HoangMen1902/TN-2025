<?php

namespace App\Filament\Resources\UserResource\Widgets;

use Filament\Widgets\ChartWidget;

class UserChart extends ChartWidget
{
    protected static ?string $heading = 'Thống kê User Demo';

    protected function getData(): array
    {
        // Demo dữ liệu tĩnh
        return [
            'datasets' => [
                [
                    'label' => 'Số lượng',
                    'data' => [5, 10, 7, 3, 8],
                    'backgroundColor' => [
                        '#f87171', '#60a5fa', '#34d399', '#fbbf24', '#a78bfa'
                    ],
                ],
            ],
            'labels' => ['A', 'B', 'C', 'D', 'E'],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}