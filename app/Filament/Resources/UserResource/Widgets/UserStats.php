<?php

namespace App\Filament\Resources\UserResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\User;
use Illuminate\Support\Carbon;

class UserStats extends BaseWidget
{
    protected function getCards(): array
    {
        $totalUsers = User::count();
        $usersOrdered = User::whereHas('orders')->count();
        $usersRegisteredThisMonth = User::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        return [
            Stat::make('Tổng số người dùng', $totalUsers),
            Stat::make('Người dùng đã đặt hàng', $usersOrdered),
            Stat::make('Đăng ký mới tháng này', $usersRegisteredThisMonth),
        ];
    }
}