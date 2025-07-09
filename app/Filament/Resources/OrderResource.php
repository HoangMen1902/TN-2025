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
use App\Services\GhnService;
use Illuminate\Support\Facades\Log;

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

                // Thêm cột shipping status
                TextColumn::make('shipping_status')
                    ->label('TT Vận chuyển')
                    ->badge()
                    ->formatStateUsing(fn($state) => \App\Models\Order::getShippingStatuses()[$state] ?? $state)
                    ->color(fn(string $state): string => match ($state) {
                        'chua_dang_don' => 'gray',
                        'da_tao_don', 'dang_lay_hang' => 'warning',
                        'da_lay_hang', 'dang_van_chuyen' => 'info',
                        'da_giao_thanh_cong' => 'success',
                        'giao_hang_that_bai', 'co_su_co' => 'danger',
                        'cho_tra_lai', 'da_tra_lai' => 'secondary',
                        'da_huy' => 'gray',
                        default => 'gray',
                    }),

                // Thêm cột mã vận đơn
                TextColumn::make('shipping_order_code')
                    ->label('Mã vận đơn')
                    ->searchable()
                    ->placeholder('Chưa có'),

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
                        ->label('Duyệt đơn & Đăng GHN')
                        ->icon('heroicon-o-check')
                        ->visible(fn(Order $record) => !$record->is_approved)
                        ->action(function (Order $record) {
                            $paymentMethod = $record->paymentDetail->payment_method ?? 'cod';

                            // Duyệt đơn hàng
                            $record->is_approved = true;

                            if ($paymentMethod === 'cod') {
                                $record->orders_status = 'đang xử lý';
                            } else {
                                $record->orders_status = 'đã thanh toán';
                            }

                            $record->save();

                            // Debug: Log thông tin đơn hàng
                            Log::info('Bắt đầu đăng đơn GHN', [
                                'order_id' => $record->id,
                                'customer_name' => $record->customer_name,
                                'phone' => $record->phone,
                                'address' => $record->address,
                                'payment_method' => $paymentMethod,
                                'total_price' => $record->total_price
                            ]);

                            // Tự động đăng đơn lên GHN
                            try {
                                $ghnService = new GhnService();

                                // Debug: Test connection trước
                                $testResult = $ghnService->testConnection();
                                Log::info('GHN Test Connection', $testResult);

                                $result = $ghnService->createOrderFromOrder($record->fresh());

                                Log::info('GHN Create Order Result', [
                                    'result' => $result,
                                    'order_id' => $record->id
                                ]);

                                if ($result && isset($result['data'])) {
                                    \Filament\Notifications\Notification::make()
                                        ->title('Thành công!')
                                        ->body("Đã duyệt đơn và đăng lên GHN thành công!\nMã vận đơn: {$record->fresh()->shipping_order_code}")
                                        ->success()
                                        ->send();

                                    activity()
                                        ->causedBy(Auth::user())
                                        ->performedOn($record)
                                        ->log("Duyệt đơn và đăng GHN: {$record->fresh()->shipping_order_code}");
                                } else {
                                    // Hiển thị lỗi chi tiết hơn
                                    $errorMsg = 'Không thể đăng lên GHN.';
                                    if (is_array($result) && isset($result['message'])) {
                                        $errorMsg .= ' Lỗi: ' . $result['message'];
                                    }

                                    \Filament\Notifications\Notification::make()
                                        ->title('Cảnh báo!')
                                        ->body($errorMsg)
                                        ->warning()
                                        ->send();
                                }
                            } catch (\Exception $e) {
                                Log::error('Lỗi đăng GHN: ' . $e->getMessage(), [
                                    'trace' => $e->getTraceAsString(),
                                    'order_id' => $record->id
                                ]);

                                \Filament\Notifications\Notification::make()
                                    ->title('Lỗi!')
                                    ->body('Lỗi khi đăng GHN: ' . $e->getMessage())
                                    ->danger()
                                    ->send();
                            }
                        })
                        ->color('success')
                        ->requiresConfirmation()
                        ->modalHeading('Xác nhận duyệt đơn')
                        ->modalSubheading('Sau khi duyệt, đơn hàng sẽ tự động được đăng lên GHN để vận chuyển.'),
                    Action::make('trackOrder')
                        ->label('Tracking')
                        ->icon('heroicon-o-magnifying-glass')
                        ->visible(fn(Order $record) => !empty($record->shipping_order_code))
                        ->action(function (Order $record) {
                            $ghnService = new GhnService();
                            $result = $ghnService->trackOrder($record->shipping_order_code);

                            if ($result && isset($result['data'])) {
                                $status = $result['data']['status'] ?? 'Không xác định';
                                \Filament\Notifications\Notification::make()
                                    ->title('Trạng thái đơn hàng')
                                    ->body("Mã vận đơn: {$record->shipping_order_code}\nTrạng thái: {$status}")
                                    ->info()
                                    ->send();
                            }
                        })
                        ->color('info'),

                    Action::make('cancelOrder')
                        ->label('Hủy đơn')
                        ->icon('heroicon-o-x-mark')
                        ->visible(function (Order $record) {
                            $allowStatuses = ['đang xử lý', 'chờ thanh toán', 'đã thanh toán', 'chờ duyệt'];
                            return in_array(strtolower($record->orders_status), $allowStatuses) &&
                                !$record->shipping_order_code; // Không cho hủy nếu đã đăng GHN
                        })
                        ->action(function (Order $record) {
                            $record->orders_status = 'đã hủy';
                            $record->shipping_status = \App\Models\Order::SHIPPING_STATUS_DA_HUY;
                            $record->save();

                            activity()
                                ->causedBy(Auth::user())
                                ->performedOn($record)
                                ->log('Hủy đơn hàng: ' . $record->id);
                        })
                        ->color('danger')
                        ->requiresConfirmation(),
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
