<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StaffResource\Pages;
use App\Models\User;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Columns\ToggleColumn;

class StaffResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Quản lý phân quyền';
    protected static ?string $navigationLabel = 'Nhân viên';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScope(SoftDeletingScope::class)
            ->role(['product staff', 'sales staff', 'marketing staff', 'super_admin']);
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('name')->label('Họ tên')->required(),
            TextInput::make('email')->label('Email')->email()->required()->unique(ignoreRecord: true),
            TextInput::make('phone')->label('Số điện thoại'),
            Select::make('gender')->label('Giới tính')->options([
                'male' => 'Nam',
                'female' => 'Nữ',
            ]),
            DatePicker::make('birthday')->label('Ngày sinh'),

            // ✅ Mật khẩu khi tạo mới
            TextInput::make('password')
                ->label('Mật khẩu')
                ->password()
                ->required(fn ($livewire) => $livewire instanceof Pages\CreateStaff)
                ->dehydrated(fn ($state) => filled($state))
                ->same('passwordConfirmation')
                ->minLength(6)
                ->maxLength(60)
                ->autocomplete('new-password'),

            TextInput::make('passwordConfirmation')
                ->label('Xác nhận mật khẩu')
                ->password()
                ->required(fn ($livewire) => $livewire instanceof Pages\CreateStaff)
                ->dehydrated(false)
                ->autocomplete('new-password'),

            Select::make('roles')
                ->label('Vai trò')
                ->relationship('roles', 'name')
                ->preload()
                ->multiple()
                ->required(),

            Toggle::make('user_status_bool')
                ->label('Trạng thái')
                ->onColor('success')
                ->offColor('gray')
                ->onIcon('heroicon-m-check')
                ->offIcon('heroicon-m-x-mark')
                ->inline(false)
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Họ tên')->searchable(),
                TextColumn::make('email')->label('Email')->searchable(),
                TextColumn::make('phone')->label('SĐT'),
                TextColumn::make('gender')->label('Giới tính'),
                TextColumn::make('birthday')->label('Ngày sinh')->date(),

                // ✅ Vai trò với badge và màu
                TextColumn::make('roles.name')
                    ->label('Vai trò')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'super_admin' => 'danger',
                        'product staff' => 'info',
                        'sales staff' => 'success',
                        'marketing staff' => 'warning',
                        default => 'gray',
                    }),

                ToggleColumn::make('user_status_bool')
                    ->label('Trạng thái')
                    ->afterStateUpdated(function ($record, $state) {
                        $record->user_status = $state ? 'active' : 'inactive';
                        $record->save();
                    }),
            ])
            ->filters([
                TrashedFilter::make(),
                SelectFilter::make('roles')
                    ->label('Vai trò')
                    ->relationship('roles', 'name'),
                SelectFilter::make('user_status')
                    ->label('Trạng thái')
                    ->options([
                        'active' => 'Đang hoạt động',
                        'inactive' => 'Ngưng hoạt động',
                    ])
            ])
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStaff::route('/'),
            'create' => Pages\CreateStaff::route('/create'),
            'edit' => Pages\EditStaff::route('/{record}/edit'),
        ];
    }
}
