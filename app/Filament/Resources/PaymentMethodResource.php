<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentMethodResource\Pages;
use App\Filament\Resources\PaymentMethodResource\RelationManagers;
use App\Models\PaymentMethod;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions\EditAction;
use Illuminate\Database\Eloquent\Builder;

class PaymentMethodResource extends Resource
{
    protected static ?string $model = PaymentMethod::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';
    protected static ?string $navigationGroup = 'Quản lý tích hợp';
    protected static ?string $navigationLabel = 'Phương thức thanh toán';
    protected static ?string $pluralModelLabel = 'Phương thức thanh toán';
    protected static ?string $modelLabel = 'Phương thức thanh toán';

    protected static bool $shouldRegisterNavigation = true;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('method_name')
                    ->label('Tên phương thức')
                    ->required()
                    ->disabled(),
                Forms\Components\Select::make('method_status')
                    ->label('Trạng thái')
                    ->options([
                        'active' => 'Hoạt động',
                        'inactive' => 'Khóa',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('method_name')
                    ->label('Tên phương thức')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('method_status')
                    ->label('Trạng thái')
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'active' => 'Hoạt động',
                        'inactive' => 'Khóa',
                        default => 'Không xác định',
                    })
                    ->sortable()
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'active' => 'success',
                        'inactive' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('updated_at')
                    ->label('Cập nhật lần cuối')
                    ->dateTime('d/m/Y H:i:s')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('method_status')
                    ->label('Lọc theo trạng thái')
                    ->options([
                        'active' => 'Hoạt động',
                        'inactive' => 'Khóa',
                    ]),
            ])
            ->actions([
                EditAction::make()
                    ->label('Bật/Tắt')
                    ->form([
                        Select::make('method_status')
                            ->label('Trạng thái')
                            ->options([
                                'active' => 'Hoạt động',
                                'inactive' => 'Khóa',
                            ])
                            ->required(),
                    ])
                    ->mutateFormDataUsing(function (array $data): array {
                        return $data;
                    }),
            ])
            ->bulkActions([])
            ->emptyStateActions([]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPaymentMethods::route('/'),
            'edit' => Pages\EditPaymentMethod::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canDeleteAny(): bool
    {
        return false;
    }
}