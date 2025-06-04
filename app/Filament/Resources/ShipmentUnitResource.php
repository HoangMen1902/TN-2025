<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ShipmentUnitResource\Pages;
use App\Filament\Resources\ShipmentUnitResource\RelationManagers;
use App\Models\Provider;
use App\Models\ShipmentUnit;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ShipmentUnitResource extends Resource
{
    protected static ?string $model = Provider::class;
    protected static ?string $navigationGroup = 'Quản lý tích hợp';

    protected static ?string $navigationLabel = 'Đơn vị vận chuyển';
    protected static ?string $pluralModelLabel = 'Đơn vị vận chuyển';
    protected static ?string $modelLabel = 'Đơn vị vận chuyển';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function canCreate(): bool
    {
        return false;
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('provider_token')->label('Token')->password(),
                TextInput::make('token_expired_time')->label('Thời gian hết hạn')->disabled(),
                Toggle::make('provider_status_bool')
                ->label('Trạng thái')
                ->afterStateUpdated(function ($record, $state) {
                    $record->provider_status_bool = $state; 
                    $record->save();
                }),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('ID'),
                TextColumn::make('provider_name')->label('Tên đơn vị'),
                ToggleColumn::make('provider_status_bool')
                ->label('Trạng thái')
                ->afterStateUpdated(function ($record, $state) {
                    $record->provider_status_bool = $state; 
                    $record->save();
                }),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListShipmentUnits::route('/'),
            'create' => Pages\CreateShipmentUnit::route('/create'),
            'edit' => Pages\EditShipmentUnit::route('/{record}/edit'),
        ];
    }
}
