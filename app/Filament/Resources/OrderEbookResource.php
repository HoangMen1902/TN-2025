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
                TextColumn::make('id')->label('Mã đơn')->sortable()->searchable(),
                TextColumn::make('user.name')->label('Khách hàng')->searchable(),
                TextColumn::make('orderDetails.0.ebook.title') 
                    ->label('Ebook')
                    ->placeholder('Nhiều Ebook')
                    ->wrap()
                    ->limit(40),
                TextColumn::make('total_price')
                    ->label('Tổng tiền')
                    ->money('VND', locale: 'vi_VN')
                    ->sortable(),
                TextColumn::make('paymentDetail.is_paid')
                    ->label('Thanh toán')
                    ->badge()
                    ->formatStateUsing(fn($state) => $state ? 'Thành công' : 'Chưa thanh toán')
                    ->color(fn($state) => $state ? 'success' : 'danger'),
                TextColumn::make('paymentDetail.payment_method')
                    ->label('Phương thức thanh toán')
                    ->default('Chưa có'),
                TextColumn::make('created_at')
                    ->dateTime('d/m/Y H:i')
                    ->label('Ngày đặt'),
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
                Tables\Filters\Filter::make('is_paid')
                    ->label('Trạng thái thanh toán')
                    ->form([
                        \Filament\Forms\Components\Select::make('value')
                            ->label('Trạng thái thanh toán')
                            ->options([
                                '1' => 'Đã thanh toán',
                                '0' => 'Chưa thanh toán',
                            ])
                    ])
                    ->query(function ($query, array $data) {
                        return $query->when($data['value'] !== null, function ($query) use ($data) {
                            return $query->whereHas('paymentDetail', function ($q) use ($data) {
                                $q->where('is_paid', $data['value']);
                            });
                        });
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
            'index' => Pages\ListOrderEbooks::route('/'),
        ];
    }
}
