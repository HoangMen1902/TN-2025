<?php

namespace App\Filament\Resources\UserResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\User;
use Illuminate\Support\Carbon;
use Filament\Support\Enums\IconPosition;

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
            Stat::make('Tổng số người dùng', $totalUsers)
                ->description('Tất cả tài khoản đã đăng ký')
                ->descriptionIcon('heroicon-m-user-group', IconPosition::Before)
                ->color('primary')
                ->chart([10, 20, 30, 40, 50, 60]),

            Stat::make('Người dùng đã đặt hàng', $usersOrdered)
                ->description('Đã từng phát sinh đơn hàng')
                ->descriptionIcon('heroicon-m-shopping-cart', IconPosition::Before)
                ->color('success')
                ->chart([2, 5, 8, 12, 15, 20]),

            Stat::make('Đăng ký mới tháng này', $usersRegisteredThisMonth)
                ->description('Tài khoản mới trong tháng')
                ->descriptionIcon('heroicon-m-user-plus', IconPosition::Before)
                ->color('warning')
                ->chart([1, 3, 5, 7, 9, 12]),
        ];
    }
}
