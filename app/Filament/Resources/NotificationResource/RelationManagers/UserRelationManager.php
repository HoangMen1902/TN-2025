<?php

namespace App\Filament\Resources\NotificationResource\RelationManagers;

use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Actions\DetachAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserRelationManager extends RelationManager
{
    protected static string $relationship = 'users';
    protected static ?string $label = 'Người dùng';

    protected static ?string $modelLabel = "Người dùng";
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
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
            ->headerActions([
                AttachAction::make()
                    ->preloadRecordSelect()
                    ->recordSelectSearchColumns(['name', 'email'])
                    ->label('Thêm người dùng'),
                Action::make('attachAll')
                    ->label('Gửi cho tất cả người dùng')
                    ->action(function () {
                        $this->getOwnerRecord()->users()->sync(User::all()->pluck('id')->toArray());
                    })
                    ->icon('heroicon-o-check-circle'),
            ])
            ->actions([
                DetachAction::make()->label('Gỡ thông báo')
            ]);
    }

    public function getTableHeading(): string {
        return 'Người dùng';
    }
}
