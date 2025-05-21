<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FlashSaleResource\Pages;
use App\Models\FlashSale;
use App\Models\ProductSku;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Form as ResourceForm;
use Filament\Resources\Resource;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Table;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Actions\ForceDeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Tables\Actions\ForceDeleteBulkAction;


class FlashSaleResource extends Resource
{
    protected static ?string $model = FlashSale::class;

    protected static ?string $navigationIcon = 'heroicon-o-bolt';
    protected static ?string $navigationLabel = 'Flash Sales';
    protected static ?string $modelLabel = 'Flash Sale';
    protected static ?string $pluralModelLabel = 'Flash Sales';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Thông tin chương trình')
                    ->schema([
                        TextInput::make('name')
                            ->label('Tên Flash Sale')
                            ->required()
                            ->maxLength(255),

                        DateTimePicker::make('started_at')
                            ->label('Thời gian bắt đầu')
                            ->required(),

                        DateTimePicker::make('expired_at')
                            ->label('Thời gian kết thúc')
                            ->required(),
                    ])->columns(2),

                Section::make('Giảm giá áp dụng')
                    ->relationship('discount')
                    ->schema([
                        Select::make('discount_type')
                            ->label('Loại giảm')
                            ->required()
                            ->options([
                                'percent' => 'Phần trăm (%)',
                                'amount' => 'Giá trị cố định',
                            ]),

                        TextInput::make('discount_amount')
                            ->label('Giá trị')
                            ->numeric()
                            ->required(),
                    ])->columns(2),

                Section::make('Hình thức áp dụng')
                    ->schema([
                        Radio::make('apply_type')
                            ->label('Chọn cách áp dụng Flash Sale')
                            ->options([
                                'sku' => 'Theo SKU sản phẩm',
                                'category' => 'Theo danh mục',
                                'both' => 'Cả hai',
                            ])
                            ->default('sku')
                            ->inline()
                            ->required(),
                    ]),

                Section::make('Sản phẩm áp dụng')
                    ->schema([
                        CheckboxList::make('skus')
                            ->label('Chọn SKU cụ thể')
                            ->relationship('skus', 'sku')
                            ->columns(2)
                            ->searchable()
                            ->visible(fn(callable $get) => in_array($get('apply_type'), ['sku', 'both']))
                            ->required(fn(callable $get) => in_array($get('apply_type'), ['sku', 'both'])),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Tên chương trình')->searchable(),
                TextColumn::make('started_at')->label('Bắt đầu')->dateTime(),
                TextColumn::make('expired_at')->label('Kết thúc')->dateTime(),
                TextColumn::make('discount.discount_type')->label('Loại giảm'),
                TextColumn::make('discount.discount_amount')->label('Giá trị'),
                TextColumn::make('skus_count')
                    ->label('Số SKU')
                    ->counts('skus')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
                RestoreAction::make(),
                ForceDeleteAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
                RestoreBulkAction::make(),
                ForceDeleteBulkAction::make(),
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
            'index' => Pages\ListFlashSales::route('/'),
            'create' => Pages\CreateFlashSale::route('/create'),
            'edit' => Pages\EditFlashSale::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
