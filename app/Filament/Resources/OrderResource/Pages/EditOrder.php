<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Pages\EditRecord;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $this->canEditStatus = empty($data['reason']);
        return $data;
    }

    protected bool $canEditStatus = true;

    protected function getFormSchema(): array
    {
        return [
            Select::make('orders_status')
                ->label('Trạng thái')
                ->options([
                    'Chờ xử lý' => 'Chờ xử lý',
                    'Vận chuyển' => 'Vận chuyển',
                    'Hoàn thành' => 'Hoàn thành',
                    'Hủy' => 'Hủy',
                ])
                ->disabled(!$this->canEditStatus) 
                ->required(),

            TextInput::make('reason')
                ->default('Lỗi hệ thống')
                ->hidden(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['reason'] = 'Lỗi hệ thống';

        return $data;
    }
}
