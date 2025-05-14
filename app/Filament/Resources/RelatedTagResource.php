<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RelatedTagResource\Pages;
use App\Models\RelatedTag;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Actions\ForceDeleteAction;
use Illuminate\Validation\ValidationException;

class RelatedTagResource extends Resource
{
    protected static ?string $model = RelatedTag::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';
    protected static ?string $navigationGroup = 'Quản lý Sản phẩm';
    protected static ?string $navigationLabel = 'Tag liên quan';
    protected static ?string $modelLabel = 'Tag liên quan';
    protected static ?string $pluralModelLabel = 'Các Tag liên quan';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('tag_name')
                ->label('Tên Tag')
                ->required()
                ->maxLength(255)
                ->reactive()
                ->unique(table: 'related_tags', column: 'tag_name', ignoreRecord: true),
            Forms\Components\Toggle::make('related_tags_status')
                ->label('Kích hoạt')
                ->default(true),
        ]);
    }




    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tag_name')
                    ->label('Tên Tag')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\IconColumn::make('related_tags_status')
                    ->label('Trạng thái')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Ngày tạo')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('related_tags_status')
                    ->label('Trạng thái')
                    ->options([
                        true => 'Kích hoạt',
                        false => 'Ẩn',
                    ]),

                Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('from')->label('Từ ngày'),
                        Forms\Components\DatePicker::make('until')->label('Đến ngày'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when($data['from'], fn($q) => $q->whereDate('created_at', '>=', $data['from']))
                            ->when($data['until'], fn($q) => $q->whereDate('created_at', '<=', $data['until']));
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                RestoreAction::make(),
                ForceDeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\RestoreBulkAction::make(),
                Tables\Actions\ForceDeleteBulkAction::make(),
            ])
            ->emptyStateHeading('Chưa có Tag liên quan nào')
            ->emptyStateDescription('Bạn có thể tạo mới Tag liên quan bằng nút bên dưới.')
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRelatedTags::route('/'),
            'create' => Pages\CreateRelatedTag::route('/create'),
            'edit' => Pages\EditRelatedTag::route('/{record}/edit'),
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
