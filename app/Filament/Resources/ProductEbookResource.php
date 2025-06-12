<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductEbookResource\Pages;
use App\Filament\Resources\ProductEbookResource\RelationManagers;
use App\Models\ProductEbook;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Smalot\PdfParser\Parser;

use function Laravel\Prompts\select;

class ProductEbookResource extends Resource
{
    protected static ?string $model = ProductEbook::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Quản lý Sản phẩm';
    protected static ?string $navigationLabel = 'Ebook';
    protected static ?string $pluralModelLabel = 'Ebook';
    protected static ?string $modelLabel = 'Ebook';
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('product_id')
                ->label('Sản phẩm')
                ->relationship('product', 'name')
                ->searchable(),

            FileUpload::make('filepath')
                ->label('Tệp PDF')
                ->disk('public')
                ->directory('ebooks')
                ->acceptedFileTypes(['application/pdf'])
                ->required(),

            TextInput::make('price')
                ->label('Giá')
                ->numeric()
                ->required(),

            Select::make('ebook_status')
                ->label('Trạng thái')
                ->options([
                    'active' => 'Kích hoạt',
                    'inactive' => 'Tạm ngưng',
                ])
                ->required(),
        ]);
    }



    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('id'),
            TextColumn::make('product.name')->label('Sản phẩm')->sortable()->limit(30)->searchable(),
            TextColumn::make('price')->label('Giá')->money('VND')->sortable(),
            TextColumn::make('content')
                ->label('Tên Combo')
                ->limit(30),
            TextColumn::make('ebook_status')->label('Trạng thái')->badge()->formatStateUsing(function ($state) {
                return match ($state) {
                    'active' => 'Hoạt động',
                    'inactive' => 'Khóa',
                    default => 'Không xác định'
                };
            })->color(fn($state) => $state === 'active' ? 'success' : 'danger')->searchable(),
            TextColumn::make('created_at')->label('Tạo lúc')->dateTime('d/m/Y H:i'),
        ])
            ->filters([])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
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
            'index' => Pages\ListProductEbooks::route('/'),
            'create' => Pages\CreateProductEbook::route('/create'),
            'edit' => Pages\EditProductEbook::route('/{record}/edit'),
        ];
    }
}
