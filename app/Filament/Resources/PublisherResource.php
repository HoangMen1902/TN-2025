<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PublisherResource\Pages;
use App\Filament\Resources\PublisherResource\RelationManagers;
use App\Models\Publisher;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\ForceDeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\ForceDeleteBulkAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TextInput;

class PublisherResource extends Resource
{
    protected static ?string $model = Publisher::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?string $navigationGroup = 'Quản lý Sản phẩm';

    protected static ?string $navigationLabel = 'Nhà xuất bản';
    protected static ?string $modelLabel = 'Nhà xuất bản';
    protected static ?string $pluralModelLabel = 'Các Nhà xuất bản';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('publisher_name')
                    ->label('Tên nhà xuất bản')
                    ->required()
                    ->maxLength(255),
                Toggle::make('publisher_status')
                    ->label('Kích hoạt')
                    ->default(true)
                    ->columnSpan(2),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('publisher_name')
                    ->label('Tên nhà xuất bản')
                    ->searchable(),
                TextColumn::make('publisher_status')
                    ->label('Trạng thái')
                    ->badge()
                    ->formatStateUsing(fn($state) => $state === 'active' ? 'Hoạt động' : 'Khóa')
                    ->color(fn($state) => $state === 'active' ? 'success' : 'danger'),
                TextColumn::make('products_count')
                    ->label('Số sản phẩm')
                    ->counts('products'),
                TextColumn::make('deleted_at')
                    ->label('Ngày xóa')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->actions([
                ActionGroup::make([
                    EditAction::make()->label('Sửa'),
                    DeleteAction::make()->label('Xóa'),
                    RestoreAction::make()->label('Khôi phục'),
                    ForceDeleteAction::make()->label('Xóa vĩnh viễn'),
                ]),
            ])
            ->bulkActions([
                DeleteBulkAction::make()->label('Xóa nhiều'),
                ForceDeleteBulkAction::make()->label('Xóa vĩnh viễn nhiều'),
                RestoreBulkAction::make()->label('Khôi phục nhiều'),
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
            'index' => Pages\ListPublishers::route('/'),
            'create' => Pages\CreatePublisher::route('/create'),
            'edit' => Pages\EditPublisher::route('/{record}/edit'),
        ];
    }
}
