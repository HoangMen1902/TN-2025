<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WonPrizeResource\Pages;
use App\Filament\Resources\WonPrizeResource\RelationManagers;
use App\Models\WonPrize;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class WonPrizeResource extends Resource
{
    protected static ?string $model = WonPrize::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard';
    protected static ?string $navigationLabel = 'Lịch sử trúng thưởng';
    protected static ?string $pluralModelLabel = 'Lịch sử trúng thưởng';
    protected static ?string $navigationGroup = 'Quản lí Mini Game';

     public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('username')->required()->label('Người trúng'),
            Forms\Components\TextInput::make('user_id')->numeric()->label('ID người dùng'),
            Forms\Components\DateTimePicker::make('won_at')->required()->label('Thời gian trúng'),
            Forms\Components\Select::make('prize_id')
                ->relationship('prize', 'name')
                ->label('Phần thưởng')
                ->required(),
                Forms\Components\Select::make('voucher_id')
    ->relationship('voucher', 'voucher_code')
    ->label('Mã voucher trúng')
    ->searchable()
    ->preload()
    ->nullable()
    ->helperText('Tự động gán nếu phần thưởng có voucher liên kết'),

        ]);
    }

    public static function table(Table $table): Table
{
    return $table
        ->columns([
            Tables\Columns\TextColumn::make('username')->label('Người trúng'),
            Tables\Columns\TextColumn::make('user_id')->label('ID'),
            Tables\Columns\TextColumn::make('prize.name')->label('Phần thưởng'),
            Tables\Columns\TextColumn::make('won_at')->dateTime('d/m/Y H:i')->label('Thời gian'),
            Tables\Columns\TextColumn::make('voucher.voucher_code')
                ->label('Mã voucher')
                ->sortable()
                ->copyable()
                ->color('info'),
        ])
        ->actions([]);
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
            'index' => Pages\ListWonPrizes::route('/'),
            
        ];
    }
}
