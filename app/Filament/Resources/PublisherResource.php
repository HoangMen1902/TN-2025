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
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\ToggleColumn;

class PublisherResource extends Resource
{
    protected static ?string $model = Publisher::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?string $navigationGroup = 'Quản lý Sản phẩm';

    protected static ?string $navigationLabel = 'Nhà xuất bản';
    protected static ?string $modelLabel = 'Nhà xuất bản';
    protected static ?string $pluralModelLabel = 'Các Nhà xuất bản';
    protected static ?int $navigationSort = 5;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
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
                ToggleColumn::make('publisher_status_bool')
                    ->label('Trạng thái')
                    ->afterStateUpdated(function ($record, $state) {
                        $record->publisher_status_bool = $state;
                        $record->save();
                    }),
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
                Tables\Filters\TrashedFilter::make()->default('with'),

                SelectFilter::make('publisher_status')
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
                ActionGroup::make([
                    EditAction::make()->label('Sửa'),
                    DeleteAction::make()
                        ->action(function ($record) {
                            $record->delete();
                            activity()
                                ->causedBy(Auth::user())
                                ->performedOn($record)
                                ->log('Xóa nhà xuất bản: ' . $record->publisher_name);
                        }),
                    RestoreAction::make()
                        ->action(function ($record) {
                            $record->restore();
                            activity()
                                ->causedBy(Auth::user())
                                ->performedOn($record)
                                ->log('Khôi phục nhà xuất bản: ' . $record->publisher_name);
                        }),
                    ForceDeleteAction::make()
                        ->action(function ($record) {
                            $record->forceDelete();
                            activity()
                                ->causedBy(Auth::user())
                                ->performedOn($record)
                                ->log('Xóa vĩnh viễn nhà xuất bản: ' . $record->publisher_name);
                        }),
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
