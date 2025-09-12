<?php

namespace App\Filament\Resources\OrderEbookResource\Pages;

use App\Filament\Resources\OrderEbookResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Forms\Components\Select;

class EditOrderEbook extends EditRecord
{
    protected static string $resource = OrderEbookResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getFormSchema(): array
    {
        return [
            Select::make('orders_status')
                ->label('Trạng thái')
                ->options([
                    'Chờ thanh toán' => 'Chờ thanh toán',
                    'Đang xử lý' => 'Đang xử lý',
                    'Đã thanh toán' => 'Đã thanh toán',
                    'Vận chuyển' => 'Vận chuyển',
                    'Hoàn thành' => 'Hoàn thành',
                    'Đã hủy' => 'Đã hủy',
                ])
                ->required(),
        ];
    }
}