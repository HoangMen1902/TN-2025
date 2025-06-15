<?php

namespace App\Filament\Resources;

use App\Models\Order;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use App\Filament\Resources\OrderResource\Pages;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Illuminate\Support\Facades\Auth;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationGroup = 'Quản lý đơn hàng';
    protected static ?string $navigationLabel = 'Đơn hàng';
    protected static ?string $pluralModelLabel = 'Đơn hàng';
    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $modelLabel = 'Đơn hàng';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('Mã đơn')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('customer_name')->label('Khách hàng'),
                TextColumn::make('phone')
                    ->label('Số điện thoại')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('calculated_total_price')
                    ->label('Tổng tiền')
                    ->money('VND', locale: 'vi_VN')
                    ->state(fn($record) => $record->calculated_total_price),

                TextColumn::make('orders_status')->label('Trạng thái'),
                TextColumn::make('is_approved')
                    ->label('Duyệt')
                    ->badge()
                    ->formatStateUsing(fn(bool $state) => $state ? 'Đã duyệt' : 'Chưa duyệt')
                    ->color(fn(bool $state) => $state ? 'success' : 'danger'),
                TextColumn::make('created_at')
                    ->dateTime('d/m/Y H:i')
                    ->label('Ngày đặt'),
            ])
            ->filters([
                Tables\Filters\Filter::make('customer_name')
                    ->form([
                        TextInput::make('value')->label('Tên khách hàng'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query->when($data['value'], fn($q, $value) =>
                        $q->where('customer_name', 'like', "%{$value}%"));
                    }),

                Tables\Filters\Filter::make('phone')
                    ->form([
                        TextInput::make('value')->label('Số điện thoại'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query->when($data['value'], fn($q, $value) =>
                        $q->where('phone', 'like', "%{$value}%"));
                    }),

                Tables\Filters\Filter::make('created_at')
                    ->form([
                        DatePicker::make('from')->label('Từ ngày'),
                        DatePicker::make('until')->label('Đến ngày'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['from'], fn($q, $date) => $q->whereDate('created_at', '>=', $date))
                            ->when($data['until'], fn($q, $date) => $q->whereDate('created_at', '<=', $date));
                    }),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Action::make('viewDetail')
                        ->label('Chi tiết')
                        ->icon('heroicon-o-eye')
                        ->modalHeading('Chi tiết đơn hàng')
                        ->modalContent(fn(Order $record) => view('filament.order-detail', ['order' => $record]))
                        ->modalSubmitAction(false)
                        ->modalCancelActionLabel('Đóng'),

                    Action::make('approve')
                        ->label('Duyệt đơn')
                        ->icon('heroicon-o-check')
                        ->visible(fn(Order $record) => !$record->is_approved) // Ẩn nếu đã duyệt
                        ->action(function (Order $record) {
                            $paymentMethod = $record->paymentDetail->payment_method ?? null;

                            $record->is_approved = true;

                            if ($paymentMethod === 'cod') {
                                $record->orders_status = 'đang xử lý';
                            } else {
                                $record->orders_status = 'đã thanh toán';
                            }

                            $record->save();
                        })
                        ->color('success'),
                    Action::make('cancelOrder')
                        ->label('Hủy đơn')
                        ->icon('heroicon-o-x-mark')
                        ->visible(function (Order $record) {
                            $allowStatuses = [
                                'Đang xử lý',
                                'Chờ thanh toán',
                                'Đã thanh toán',
                                'Chờ duyệt',
                                'Vận chuyển',
                            ];
                            return in_array(strtolower($record->orders_status), $allowStatuses);
                        })
                        ->action(function (Order $record) {
                            $record->orders_status = 'đã hủy';
                            $record->save();
                            activity()
                                ->causedBy(Auth::user())
                                ->performedOn($record)
                                ->log('Hủy đơn hàng: ' . $record->id);
                        })
                        ->color('danger'),
                    Action::make('shipOrder')
                        ->label('Đăng đơn')
                        ->icon('heroicon-o-truck')
                        ->visible(fn(Order $record) => strtolower($record->orders_status) !== 'vận chuyển')
                        ->action(function (Order $record) {
                            $record->orders_status = 'vận chuyển';
                            $record->save();

                            activity()
                                ->causedBy(Auth::user())
                                ->performedOn($record)
                                ->log('Đăng đơn hàng: ' . $record->id);
                        })
                        ->color('primary'),
                ])
            ])

            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function canCreate(): bool
    {
        return false;
    }
    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
        ];
    }
}
