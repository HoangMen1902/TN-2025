<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OptionResource\Pages;
use App\Filament\Resources\OptionResource\Pages\ViewOption;
use App\Filament\Resources\OptionResource\RelationManagers;
use App\Models\Option;
use Filament\Actions\ViewAction;
use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Database\Eloquent\SoftDeletes;
use Filament\Tables\Filters\TrashedFilter;
use Illuminate\Support\Facades\Auth;

class OptionResource extends Resource
{
    use SoftDeletes;
    protected static ?string $model = Option::class;

    protected static ?string $navigationGroup = 'Quản lý Sản phẩm';
    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    protected static ?string $label = 'Thuộc tính';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->label('Tên thuộc tính')->columnSpanFull(),
                Repeater::make('optionValues')->label('Giá trị thuộc tính')->relationship('optionValues')->schema([
                    TextInput::make('value_name')
                        ->label('Tên giá trị')
                        ->rule(['required'])
                        ->validationMessages(['required' => 'Vui lòng nhập giá trị *']),
                ])->columnSpanFull()->deletable()->addable()->minItems(1)->rules(['min:1'])->validationMessages(['min' => 'Phải có ít nhất 1 giá trị']),
                Toggle::make('option_status')
                    ->label('Kích hoạt')
                    ->default(true)
                    ->columnSpan(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('ID'),
                TextColumn::make('name')->label('Tên thuộc tính'),
                TextColumn::make('optionValues.value_name')->label('Giá trị')->limit(3),
                TextColumn::make('status')->label('Trạng thái')->formatStateUsing(function ($state) {
                    return match ($state) {
                        1 => 'Hoạt động',
                        2 => 'Khóa',
                        default => 'Không xác định',
                    };
                }),
            ])
            ->filters([
                TrashedFilter::make()->default('with'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->label('Xem chi tiết'),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->action(function ($record) {
                        $record->delete();
                        activity()
                            ->causedBy(Auth::user())
                            ->performedOn($record)
                            ->log('Xóa mềm thuộc tính: ' . $record->name);
                    }),
                Tables\Actions\RestoreAction::make()
                    ->action(function ($record) {
                        $record->restore();
                        activity()
                            ->causedBy(Auth::user())
                            ->performedOn($record)
                            ->log('Khôi phục thuộc tính: ' . $record->name);
                    }),
                Tables\Actions\ForceDeleteAction::make()
                    ->action(function ($record) {
                        $record->forceDelete();
                        activity()
                            ->causedBy(modelOrId: Auth::user())
                            ->performedOn($record)
                            ->log('Xóa vĩnh viễn thuộc tính: ' . $record->name);
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withTrashed();
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
            'index' => Pages\ListOptions::route('/'),
            'create' => Pages\CreateOption::route('/create'),
            'edit' => Pages\EditOption::route('/{record}/edit'),
            'view' => ViewOption::route('{record}')
        ];
    }
}
