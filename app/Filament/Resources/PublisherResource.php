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
use Filament\Tables\Actions\RestoreAction;

class PublisherResource extends Resource
{
    protected static ?string $model = Publisher::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Nhà xuất bản';
   public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('publisher_name')
                    ->label('Tên nhà xuất bản')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('publishers_status')
                    ->label('Trạng thái')
                    ->options([
                        'active' => 'Hoạt động',
                        'inactive' => 'Không hoạt động',
                    ])
                    ->default('active')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('publisher_name')
                    ->label('Tên nhà xuất bản')
                    ->searchable(),
                Tables\Columns\TextColumn::make('publishers_status')
                    ->label('Trạng thái')
                    ->formatStateUsing(fn (string $state): string => $state === 'active' ? 'Hoạt động' : 'Không hoạt động'),
                Tables\Columns\TextColumn::make('products_count')
                    ->label('Số sản phẩm')
                    ->counts('products'),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->label('Ngày xóa')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\EditAction::make()->label('Sửa'),
                    DeleteAction::make()->label('Xóa'),
                    RestoreAction::make()->label('Khôi phục'),
                    ForceDeleteAction::make()->label('Xóa vĩnh viễn'),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()->label('Xóa nhiều'),
                Tables\Actions\ForceDeleteBulkAction::make()->label('Xóa vĩnh viễn nhiều'),
                Tables\Actions\RestoreBulkAction::make()->label('Khôi phục nhiều'),
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
