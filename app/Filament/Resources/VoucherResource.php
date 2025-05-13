<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VoucherResource\Pages;
use App\Filament\Resources\VoucherResource\RelationManagers;
use App\Models\Voucher;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;

class VoucherResource extends Resource
{
    protected static ?string $model = Voucher::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationLabel = 'Mã giảm giá';
    protected static ?string $modelLabel = 'Mã giảm giá';
    protected static ?string $pluralModelLabel = 'Các Mã giảm giá';

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('voucher_name')
                ->label('Tên voucher')
                ->required()
                ->maxLength(length: 255),

            TextInput::make('requirement_price')
                ->label('Giá trị đơn hàng tối thiểu')
                ->numeric()
                ->required(),
            TextInput::make('reduced_amount')
                ->label('Số tiền giảm')
                ->numeric()
                ->required(),

            Select::make('voucher_type')
                ->label('Loại voucher')
                ->options([
                    'percent' => 'Phần trăm',
                    'amount' => 'Cố định',
                ])
                ->required(),

            DateTimePicker::make('expired_at')
                ->label('Ngày hết hạn')
                ->required(),

            Select::make('voucher_status')
                ->label('Trạng thái')
                ->options([
                    'active' => 'Hoạt động',
                    'inactive' => 'Không hoạt động',
                ])
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('voucher_name')->label('Tên voucher')->searchable()->sortable(),
            TextColumn::make('requirement_price')->label('Giá trị tối thiểu')->sortable(),
            TextColumn::make('reduced_amount')
                ->label('Số tiền giảm')
                ->sortable()
                ->formatStateUsing(function ($state) {
                    return number_format($state, 0, ',', ',') . ' VNĐ';
                }),

                TextColumn::make('voucher_type')
                ->label('Loại')
                ->formatStateUsing(fn($state) => match ($state) {
                    'percent' => 'Phần trăm',
                    'amount' => 'Cố định',
                    default => ucfirst($state),
                })
                ->sortable(),
            
            

            TextColumn::make('expired_at')->label('Hết hạn')->dateTime(),
            TextColumn::make('voucher_status')
                ->label('Trạng thái')
                ->badge()
                ->formatStateUsing(fn($state) => $state === 'active' ? 'Hoạt động' : 'Khóa')
                ->color(fn($state) => $state === 'active' ? 'success' : 'danger'),
        ])->filters([
            //
        ])->actions([
            Tables\Actions\EditAction::make()->label('Chỉnh sửa'),
            Tables\Actions\DeleteAction::make()->label('Xoá'),
        ])->bulkActions([
            Tables\Actions\DeleteBulkAction::make()->label('Xoá hàng loạt'),
        ]);
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
            'index' => Pages\ListVouchers::route('/'),
            'create' => Pages\CreateVoucher::route('/create'),
            'edit' => Pages\EditVoucher::route('/{record}/edit'),
        ];
    }
}
