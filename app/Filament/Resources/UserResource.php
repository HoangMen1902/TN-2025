<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\EditRecord;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-user';

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('name')
                ->label('Tên người dùng')
                ->required()
                ->maxLength(255),

            TextInput::make('email')
                ->label('Email')
                ->email()
                ->required()
                ->unique(ignoreRecord: true),

            TextInput::make('password')
                ->label('Mật khẩu')
                ->password()
                ->required(fn($livewire) => $livewire instanceof CreateRecord)
                ->hidden(fn($livewire) => $livewire instanceof EditRecord)
                ->maxLength(255),

            TextInput::make('phone')
                ->label('Số điện thoại')
                ->required()
                ->minLength(10)
                ->maxLength(10)
                ->rule('regex:/^0\d{9}$/'),

            DatePicker::make('birthday')
                ->label('Ngày sinh')
                ->nullable(),

            TextInput::make('avatar')
                ->label('Ảnh đại diện (URL)')
                ->nullable(),

            TextInput::make('score')
                ->label('Điểm')
                ->numeric()
                ->nullable(),

            Select::make('role')
                ->label('Vai trò')
                ->options([
                    'user' => 'Khách hàng',
                    'admin' => 'Quản trị',
                ])
                ->required()
                ->native(false),

            Select::make('users_status')
                ->label('Trạng thái')
                ->options([
                    'active' => 'Hoạt động',
                    'inactive' => 'Khóa',
                ])
                ->required()
                ->native(false),
        ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withTrashed();
    }
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('ID')->sortable(),
                TextColumn::make('name')->label('Tên')->searchable(),
                TextColumn::make('email')->label('Email')->searchable(),
                TextColumn::make('phone')->label('SĐT'),
                ImageColumn::make('avatar')->label('Avatar')->circular(),
                TextColumn::make('birthday')->label('Ngày sinh')->date('d/m/Y')->sortable(),
                TextColumn::make('score')->label('Điểm')->sortable(),
                TextColumn::make('users_status')
                    ->label('Trạng thái')
                    ->badge()
                    ->formatStateUsing(fn($state) => $state === 'active' ? 'Hoạt động' : 'Khóa')
                    ->color(fn($state) => $state === 'active' ? 'success' : 'danger'),
                TextColumn::make('role')
                    ->label('Vai trò')
                    ->badge()
                    ->formatStateUsing(fn($state) => $state === 'admin' ? 'Quản trị' : 'Khách hàng')
                    ->color(fn($state) => $state === 'admin' ? 'primary' : 'info'),
            ])
            ->filters([
                TrashedFilter::make(),
                SelectFilter::make('role')
                    ->label('Lọc theo vai trò')
                    ->options([
                        'admin' => 'Quản trị',
                        'user' => 'Khách hàng',
                    ]),

                SelectFilter::make('status')
                    ->label('Lọc theo trạng thái')
                    ->options([
                        'active' => 'Hoạt động',
                        'inactive' => 'Khóa',
                    ]),

                TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()->action(function($record) {
                    if ($record->id === Auth::id()) {
                        Notification::make()
                            ->title('Không thể xóa tài khoản đang sử dụng.')
                            ->danger()
                            ->send();
                        return;
                    }

                    $record->users_status = 'inactive';
                    $record->save();
                    $record->delete();
                }),
                Tables\Actions\RestoreAction::make()->action(function($record) {
                    $record->users_status = 'active';
                    $record->save();

                    $record->restore();
                }),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ])
            ->defaultSort('id', 'desc')
            ->modifyQueryUsing(fn(Builder $query) => $query->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]));
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getPluralLabel(): string
    {
        return 'Người dùng';
    }

    public static function getLabel(): string
    {
        return 'Người dùng';
    }
}
