<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MembershipResource\Pages;
use App\Models\Membership;
use App\Models\Voucher;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\DateTimePicker;

class MembershipResource extends Resource
{
    protected static ?string $model = Membership::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Hạng thành viên';
    protected static ?string $modelLabel = 'Hạng thành viên';
    protected static ?string $pluralModelLabel = 'Các hạng thành viên';
    protected static ?string $navigationGroup = 'Chương trình giảm giá';
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('name')
                ->label('Tên hạng')
                ->required()
                ->maxLength(100),

            TextInput::make('required_points')
                ->label('Điểm yêu cầu')
                ->numeric()
                ->minValue(0)
                ->required(),

            Textarea::make('benefits')
                ->label('Quyền lợi / Ghi chú')
                ->rows(3)
                ->maxLength(1000)
                ->nullable(),

            Select::make('status')
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
        return $table->columns([
            TextColumn::make('name')
                ->label('Tên hạng')
                ->sortable()
                ->searchable(),

            TextColumn::make('required_points')
                ->label('Điểm yêu cầu')
                ->sortable(),

            TextColumn::make('status')
                ->label('Trạng thái')
                ->badge()
                ->color(fn($state) => $state === 'active' ? 'success' : 'danger')
                ->formatStateUsing(fn($state) => $state === 'active' ? 'Hoạt động' : 'Không hoạt động'),

            TextColumn::make('created_at')
                ->label('Ngày tạo')
                ->dateTime()
                ->sortable(),
        ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make()->label('Chỉnh sửa'),
                Tables\Actions\DeleteAction::make()->label('Xóa'),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()->label('Xoá hàng loạt'),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMemberships::route('/'),
            'create' => Pages\CreateMembership::route('/create'),
            'edit' => Pages\EditMembership::route('/{record}/edit'),
        ];
    }
}
