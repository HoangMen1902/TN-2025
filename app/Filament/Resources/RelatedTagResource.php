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
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Actions\ForceDeleteAction;
use Illuminate\Validation\ValidationException;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Illuminate\Support\Facades\Auth;

class RelatedTagResource extends Resource
{
    protected static ?string $model = RelatedTag::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';
    protected static ?string $navigationGroup = 'Quản lý Sản phẩm';
    protected static ?string $navigationLabel = 'Thẻ sản phẩm';
    protected static ?string $modelLabel = 'Thẻ sản phẩm';
    protected static ?string $pluralModelLabel = 'Các Thẻ sản phẩm';

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('tag_name')
                ->label('Tên Tag')
                ->required()
                ->maxLength(255)
                ->reactive()
                ->unique(table: 'related_tags', column: 'tag_name', ignoreRecord: true),

            Toggle::make('related_tag_status')
                ->label('Kích hoạt')
                ->default(true)
                ->columnSpan(2),
        ]);
    }




    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tag_name')
                    ->label('Tên Tag')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('related_tag_status')
                    ->label('Trạng thái')
                    ->badge()
                    ->formatStateUsing(function ($state, $record) {
                        if ($record->deleted_at) {
                            return 'Khóa';
                        }
                        return $state ? 'Hoạt động' : 'Khóa';
                    })
                    ->color(function ($state, $record) {
                        if ($record->deleted_at) {
                            return 'danger';
                        }
                        return $state ? 'success' : 'danger';
                    }),
                TextColumn::make('created_at')
                    ->label('Ngày tạo')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('related_tag_status')
                    ->label('Trạng thái')
                    ->options([
                        true => 'Hoạt động',
                        false => 'khóa',
                    ]),

                Filter::make('created_at')
                    ->form([
                        DatePicker::make('from')->label('Từ ngày'),
                        DatePicker::make('until')->label('Đến ngày'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when($data['from'], fn($q) => $q->whereDate('created_at', '>=', $data['from']))
                            ->when($data['until'], fn($q) => $q->whereDate('created_at', '<=', $data['until']));
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->action(function ($record) {
                        $record->delete();
                        activity()
                            ->causedBy(Auth::user())
                            ->performedOn($record)
                            ->log('Xóa mềm thẻ sản phẩm: ' . $record->tag_name);
                    }),
                RestoreAction::make()
                    ->action(function ($record) {
                        $record->restore();
                        activity()
                            ->causedBy(Auth::user())
                            ->performedOn($record)
                            ->log('Khôi phục thẻ sản phẩm: ' . $record->tag_name);
                    }),
                ForceDeleteAction::make()
                    ->action(function ($record) {
                        $record->forceDelete();
                        activity()
                            ->causedBy(Auth::user())
                            ->performedOn($record)
                            ->log('Xóa vĩnh viễn thẻ sản phẩm: ' . $record->tag_name);
                    }),
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
