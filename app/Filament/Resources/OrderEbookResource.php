<?php

namespace App\Filament\Resources;

use App\Models\EbookOrder;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use App\Filament\Resources\OrderEbookResource\Pages;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Illuminate\Support\Facades\Auth;

class OrderEbookResource extends Resource
{
    protected static ?string $model = EbookOrder::class;

    protected static ?string $navigationGroup = 'Quản lý đơn hàng';
    protected static ?string $navigationLabel = 'Đơn hàng Ebook';
    protected static ?string $pluralModelLabel = 'Đơn hàng Ebook';
    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?string $modelLabel = 'Đơn hàng Ebook';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('Mã đơn')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('user.name')->label('Khách hàng'),
                TextColumn::make('total_price')
                    ->label('Tổng tiền')
                    ->money('VND', locale: 'vi_VN')
                    ->sortable(),
                TextColumn::make('orders_status')->label('Trạng thái'),
                TextColumn::make('is_approved')
                    ->label('Duyệt')
                    ->badge()
                    ->formatStateUsing(fn(bool $state) => $state ? 'Đã duyệt' : 'Chưa duyệt')
                    ->color(fn(bool $state) => $state ? 'success' : 'danger'),
                TextColumn::make('created_at')
                    ->dateTime('d/m/Y H:i')
                    ->label('Ngày đặt'),
                TextColumn::make('paymentDetail.payment_method')
                    ->label('Phương thức thanh toán')
                    ->default('Chưa có'),
            ])
            ->filters([
                Tables\Filters\Filter::make('user.name')
                    ->form([
                        TextInput::make('value')->label('Tên khách hàng'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query->when($data['value'], fn($q, $value) =>
                        $q->whereHas('user', fn($q) => $q->where('name', 'like', "%{$value}%")));
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
                        ->modalHeading('Chi tiết đơn hàng Ebook')
                        ->modalContent(fn(EbookOrder $record) => view('filament.order-ebook-detail', ['order' => $record]))
                        ->modalSubmitAction(false)
                        ->modalCancelActionLabel('Đóng'),
                    Action::make('approve')
                        ->label('Duyệt đơn')
                        ->icon('heroicon-o-check')
                        ->visible(fn(EbookOrder $record) => !$record->is_approved && $record->orders_status === 'Chờ thanh toán')
                        ->action(function (EbookOrder $record) {
                            $paymentMethod = $record->paymentDetail->payment_method ?? null;
                            $record->is_approved = true;
                            $record->orders_status = ($paymentMethod === 'cod') ? 'Đang xử lý' : 'Đã thanh toán';
                            $record->save();

                            // Gửi thông báo trạng thái đơn hàng Ebook cho user
                            \App\Services\NotificationService::send([
                                $record->user_id
                            ],
                                'Trạng thái đơn hàng Ebook thay đổi',
                                'Đơn hàng Ebook #' . $record->id . ' đã được duyệt. Trạng thái mới: ' . $record->orders_status,
                                'Đơn hàng Ebook'
                            );
                        })
                        ->color('success'),

                    Action::make('cancelOrder')
                        ->label('Hủy đơn')
                        ->icon('heroicon-o-x-mark')
                        ->visible(function (EbookOrder $record) {
                            $allowStatuses = ['Chờ thanh toán', 'Đang xử lý', 'Đã thanh toán', 'Vận chuyển'];
                            return in_array($record->orders_status, $allowStatuses);
                        })
                        ->action(function (EbookOrder $record) {
                            $record->orders_status = 'Đã hủy';
                            $record->save();

                            // Gửi thông báo trạng thái đơn hàng Ebook cho user
                            \App\Services\NotificationService::send([
                                $record->user_id
                            ],
                                'Trạng thái đơn hàng Ebook thay đổi',
                                'Đơn hàng Ebook #' . $record->id . ' đã bị hủy.',
                                'Đơn hàng Ebook'
                            );
                            activity()
                                ->causedBy(Auth::user())
                                ->performedOn($record)
                                ->log('Hủy đơn hàng Ebook: ' . $record->id);
                        })
                        ->color('danger'),

                    Action::make('shipOrder')
                        ->label('Đăng đơn')
                        ->icon('heroicon-o-truck')
                        ->visible(fn(EbookOrder $record) => $record->orders_status !== 'Vận chuyển')
                        ->action(function (EbookOrder $record) {
                            $record->orders_status = 'Vận chuyển';
                            $record->save();

                            // Gửi thông báo trạng thái đơn hàng Ebook cho user
                            \App\Services\NotificationService::send([
                                $record->user_id
                            ],
                                'Trạng thái đơn hàng Ebook thay đổi',
                                'Đơn hàng Ebook #' . $record->id . ' đã chuyển sang trạng thái Vận chuyển.',
                                'Đơn hàng Ebook'
                            );
                            activity()
                                ->causedBy(Auth::user())
                                ->performedOn($record)
                                ->log('Đăng đơn hàng Ebook: ' . $record->id);
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
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrderEbooks::route('/'),
            'edit' => Pages\EditOrderEbook::route('/{record}/edit'),
        ];
    }
}
