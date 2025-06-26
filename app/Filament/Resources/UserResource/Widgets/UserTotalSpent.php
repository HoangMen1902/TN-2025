<?php

namespace App\Filament\Resources\UserResource\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class UserTotalSpent extends ChartWidget
{
    public $record;

    protected static ?string $heading = 'Tổng số tiền đã mua';
    protected function getType(): string
    {
        return 'stat';
    }
    protected function getValue(): string
    {
        $user = $this->record ?? null;
        if (!$user) return '0 đ';

        // Nếu có cột total_amount trên orders
        $total = Order::where('user_id', $user->id)
            ->where('status', 'completed') // nếu có trạng thái hoàn thành
            ->sum('total_amount');

        // Nếu không có cột total_amount, tính tổng từ order_details:
        // $total = DB::table('order_details')
        //     ->join('orders', 'order_details.order_id', '=', 'orders.id')
        //     ->where('orders.user_id', $user->id)
        //     ->sum(DB::raw('order_details.price * order_details.quantity'));

        return number_format($total, 0, ',', '.') . ' đ';
    }
}