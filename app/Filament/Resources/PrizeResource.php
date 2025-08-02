<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PrizeResource\Pages;
use App\Filament\Resources\PrizeResource\RelationManagers;
use App\Models\Prize;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PrizeResource extends Resource
{
     protected static ?string $model = Prize::class;
    protected static ?string $navigationIcon = 'heroicon-o-gift';
    protected static ?string $navigationLabel = 'Phần thưởng';
    protected static ?string $pluralModelLabel = 'Phần thưởng';
    protected static ?string $navigationGroup = 'Quản lí Mini Game';
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
     public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')->required()->label('Tên phần thưởng'),
            Forms\Components\TextInput::make('quantity')->numeric()->label('Số lượng'),
            Forms\Components\TextInput::make('probability')->numeric()->step(0.01)->label('Xác suất trúng (%)'),
            Forms\Components\Select::make('voucher_id')
    ->label('Voucher áp dụng')
    ->relationship('voucher', 'voucher_code')
    ->searchable()
    ->preload()
    ->nullable()
    ->helperText('Liên kết phần thưởng với mã giảm giá có sẵn'),

        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('name')->label('Tên'),
            Tables\Columns\TextColumn::make('quantity')->label('Số lượng'),
            Tables\Columns\TextColumn::make('probability')->label('Xác suất'),
            Tables\Columns\TextColumn::make('created_at')->dateTime('d/m/Y H:i')->label('Tạo lúc'),
            Tables\Columns\TextColumn::make('voucher.voucher_code')->label('Mã voucher'),
        ])->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListPrizes::route('/'),
            'create' => Pages\CreatePrize::route('/create'),
            'edit' => Pages\EditPrize::route('/{record}/edit'),
        ];
    }
}
