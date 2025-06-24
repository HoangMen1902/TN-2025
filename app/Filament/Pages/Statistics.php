<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\OrdersChart;
use Filament\Pages\Page;
use App\Models\Product;
use App\Models\Publisher;
use App\Models\Order;
use App\Models\Voucher;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Statistics extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationLabel = 'Thống kê';
    protected static string $view = 'filament.pages.statistics';
    protected static ?string $navigationGroup = 'Quản lý Chung';

    public $startDate;
    public $endDate;

    public function mount()
    {
        $this->startDate = now()->subDays(30)->toDateString();
        $this->endDate = now()->toDateString();
    }

    public function getProductCount()
    {
        return Product::count();
    }

    public function getPublisherCount()
    {
        return Publisher::count();
    }
    public function getVoucherCount()
    {
        return Voucher::count();
    }
    public function getOrderCount()
    {
        return Order::whereBetween('created_at', [
            Carbon::parse($this->startDate)->startOfDay(),
            Carbon::parse($this->endDate)->endOfDay()
        ])->count();
    }


    public function getRevenue()
    {
        return Order::whereBetween('created_at', [
            Carbon::parse($this->startDate)->startOfDay(),
            Carbon::parse($this->endDate)->endOfDay()
        ])
            ->with('orderDetails')
            ->get()
            ->flatMap(fn($order) => $order->orderDetails)
            ->sum(fn($detail) => $detail->price * $detail->quantity);
    }


    public function getOrderChartData()
    {
        $start = Carbon::parse($this->startDate)->startOfDay();
        $end = Carbon::parse($this->endDate)->endOfDay();

        return Order::selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }
    private function calculateChangePercent(callable $currentQuery, callable $previousQuery)
    {
        $current = $currentQuery();
        $previous = $previousQuery();

        if ($previous == 0) {
            return $current > 0 ? 100 : 0;
        }

        return round((($current - $previous) / $previous) * 100, 2);
    }

    public function getProductChangePercent()
    {
        return $this->calculateChangePercent(
            fn() => Product::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            fn() => Product::whereBetween('created_at', [now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()])->count()
        );
    }

    public function getPublisherChangePercent()
    {
        return $this->calculateChangePercent(
            fn() => Publisher::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            fn() => Publisher::whereBetween('created_at', [now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()])->count()
        );
    }

    public function getVoucherChangePercent()
    {
        return $this->calculateChangePercent(
            fn() => Voucher::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            fn() => Voucher::whereBetween('created_at', [now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()])->count()
        );
    }
    public function getOrderChangePercent()
    {
        return $this->calculateChangePercent(
            fn() => Order::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            fn() => Order::whereBetween('created_at', [now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()])->count()
        );
    }

    public function getRevenueChangePercent()
    {
        return $this->calculateChangePercent(
            fn() => DB::table('orders')
                ->join('order_details', 'orders.id', '=', 'order_details.order_id')
                ->whereBetween('orders.created_at', [now()->startOfWeek(), now()->endOfWeek()])
                ->sum(DB::raw('order_details.price * order_details.quantity')),

            fn() => DB::table('orders')
                ->join('order_details', 'orders.id', '=', 'order_details.order_id')
                ->whereBetween('orders.created_at', [now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()])
                ->sum(DB::raw('order_details.price * order_details.quantity'))
        );
    }
}
