<?php

namespace App\Filament\Pages;
use App\Filament\Widgets\OrdersChart;
use Filament\Pages\Page;
use App\Models\Product;
use App\Models\Publisher;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Statistics extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static string $view = 'filament.pages.statistics';

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

    public function getOrderCount()
    {
        return Order::whereBetween('created_at', [$this->startDate, $this->endDate])->count();
    }

    public function getRevenue()
    {
        return DB::table('orders')
            ->join('order_details', 'orders.id', '=', 'order_details.order_id')
            ->whereBetween('orders.created_at', [$this->startDate, $this->endDate])
            ->sum(DB::raw('order_details.price * order_details.quantity'));
    }

    public function getOrderChartData()
    {
        return Order::selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }
    

}

