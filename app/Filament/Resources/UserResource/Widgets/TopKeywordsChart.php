<?php

namespace App\Filament\Resources\UserResource\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\SearchHistory;
use Illuminate\Support\Facades\Log;

class TopKeywordsChart extends ChartWidget
{
    public $record; // <-- Thêm dòng này

    protected static ?string $heading = 'Top từ khóa tìm kiếm';

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getData(): array
    {
        Log::info('Widget $this->record', ['record' => $this->record]);
        $user = $this->record ?? null;
        if (!$user) return ['datasets' => [], 'labels' => []];

        $data = SearchHistory::where('user_id', $user->id)
            ->select('keyword')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('keyword')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // Debug
        Log::info('TopKeywordsChart', ['userId' => $user->id, 'data' => $data]);
        return [
            'datasets' => [
                [
                    'label' => 'Số lần tìm',
                    'data' => $data->pluck('total')->map(fn($v) => (int)$v),
                ],
            ],
            'labels' => $data->pluck('keyword')->map(fn($v) => strlen($v) > 15 ? mb_substr($v, 0, 15) . '...' : $v),
        ];
    }
}
