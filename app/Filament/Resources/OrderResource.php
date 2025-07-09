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
use App\Services\OrderShipmentService;

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
                        ->label('Duyệt đơn & Đăng vận chuyển')
                        ->icon('heroicon-o-check')
                        ->visible(fn(Order $record) => !$record->is_approved)
                        ->action(function (Order $record) {
                            $paymentMethod = $record->paymentDetail->payment_method ?? 'cod';
                            $shipmentUnit = $record->paymentDetail->shipment_unit ?? null;

                      
                            $record->is_approved = true;

                            if ($paymentMethod === 'cod') {
                                $record->orders_status = 'đang xử lý';
                            } else {
                                $record->orders_status = 'đã thanh toán';
                            }

                            $record->save();

                      
                            Log::info('Bắt đầu đăng đơn vận chuyển', [
                                'order_id' => $record->id,
                                'customer_name' => $record->customer_name,
                                'phone' => $record->phone,
                                'address' => $record->address,
                                'payment_method' => $paymentMethod,
                                'shipment_unit' => $shipmentUnit,
                                'total_price' => $record->total_price
                            ]);

               
                            OrderShipmentService::processShipment($record, $shipmentUnit);
                        })
                        ->color('success')
                        ->requiresConfirmation()
                        ->modalHeading('Xác nhận duyệt đơn')
                        ->modalSubheading('Sau khi duyệt, đơn hàng sẽ tự động được đăng lên đơn vị vận chuyển tương ứng.'),

                    Action::make('trackOrder')
                        ->label('Tracking')
                        ->icon('heroicon-o-magnifying-glass')
                        ->visible(fn(Order $record) => !empty($record->shipping_order_code))
                        ->action(function (Order $record) {
                            try {
                                $orderCode = $record->shipping_order_code;
                                $result = null;
                                $serviceName = '';

          
                                if (str_starts_with($orderCode, 'VTP_') || str_starts_with($orderCode, 'VTP_MOCK_')) {
 
                                    $serviceName = 'Viettel Post';

                                    if (str_starts_with($orderCode, 'VTP_MOCK_')) {
                                     
                                        $statuses = [
                                            'Đã tiếp nhận',
                                            'Đang lấy hàng',
                                            'Đang vận chuyển',
                                            'Đang giao hàng',
                                            'Đã giao thành công'
                                        ];

                                        $randomStatus = $statuses[array_rand($statuses)];

                                        \Filament\Notifications\Notification::make()
                                            ->title('Trạng thái đơn hàng (Mock)')
                                            ->body("Mã vận đơn: {$orderCode}\nDịch vụ: {$serviceName}\nTrạng thái: {$randomStatus}\n\n⚠️ Đây là mock data cho demo")
                                            ->info()
                                            ->send();
                                        return;
                                    } else {
                                      
                                        \Filament\Notifications\Notification::make()
                                            ->title('Thông báo')
                                            ->body("Tracking Viettel Post đang được phát triển.\nMã vận đơn: {$orderCode}")
                                            ->warning()
                                            ->send();
                                        return;
                                    }
                                } else {
                            
                                    $serviceName = 'Giao Hàng Nhanh';
                                    $ghnService = new GhnService();
                                    $result = $ghnService->trackOrder($orderCode);
                                }

                        
                                if ($result && isset($result['data'])) {
                                    $status = $result['data']['status'] ?? 'Không xác định';
                                    $statusText = $ghnService->getStatusText($status) ?? $status;

                                    \Filament\Notifications\Notification::make()
                                        ->title('Trạng thái đơn hàng')
                                        ->body("Mã vận đơn: {$orderCode}\nDịch vụ: {$serviceName}\nTrạng thái: {$statusText}")
                                        ->info()
                                        ->send();
                                } else {
                             
                                    $errorMsg = 'Không thể lấy thông tin tracking';
                                    if (isset($result['message'])) {
                                        $errorMsg .= ': ' . $result['message'];
                                    }

                                    \Filament\Notifications\Notification::make()
                                        ->title('Lỗi tracking')
                                        ->body("Mã vận đơn: {$orderCode}\nDịch vụ: {$serviceName}\nLỗi: {$errorMsg}")
                                        ->danger()
                                        ->send();
                                }
                            } catch (\Exception $e) {
                                Log::error('Lỗi tracking đơn hàng', [
                                    'order_code' => $record->shipping_order_code,
                                    'error' => $e->getMessage()
                                ]);

                                \Filament\Notifications\Notification::make()
                                    ->title('Lỗi hệ thống')
                                    ->body("Không thể tracking đơn hàng: " . $e->getMessage())
                                    ->danger()
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
