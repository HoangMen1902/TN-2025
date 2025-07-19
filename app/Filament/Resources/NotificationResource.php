<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NotificationResource\Pages;
use App\Filament\Resources\NotificationResource\RelationManagers\UserRelationManager;
use App\Models\Notification;
use Faker\Core\Color;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class NotificationResource extends Resource
{
    protected static ?string $model = Notification::class;

    protected static ?string $navigationIcon = 'heroicon-o-bell-alert';
    protected static ?string $label = 'Thông báo';
    protected static ?string $navigationGroup = 'Quản lý Chung';

    protected static ?string $navigationLabel = 'Thông báo';
    protected static ?string $modelLabel = 'Thông báo';
    protected static ?string $pluralModelLabel = 'Các Thông báo';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->rules(['required'])
                    ->validationMessages(['required' => 'Vui lòng nhập tiêu đề thông báo'])
                    ->label('Tiêu đề')
                    ->columnSpanFull(),

                Textarea::make('content')
                    ->rules(['required'])
                    ->validationMessages(['required' => 'Vui lòng nhập nội dung thông báo'])
                    ->label('Nội dung thông báo')
                    ->columnSpanFull()
                    ->rows(4),

                Select::make('notification_type')
                    ->rules(['required'])
                    ->validationMessages(['required' => 'Vui lòng chọn loại thông báo'])
                    ->label('Loại thông báo')
                    ->options([
                        'Khuyến mãi' => 'Khuyến mãi',
                        'Đơn hàng' => 'Đơn hàng',
                        'Tài khoản' => 'Tài khoản',
                        'Ưu đãi độc quyền' => 'Ưu đãi độc quyền',
                        'Chúc mừng sinh nhật' => 'Chúc mừng sinh nhật',
                    ])
                    ->columnSpanFull(),

                FileUpload::make('thumbnail')
                    ->label('Ảnh đại diện')
                    ->image()
                    ->directory('thumbnails')
                    ->nullable()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('thumbnail')
                    ->label('Ảnh')
                    ->circular()
                    ->height(40)
                    ->width(40),

                TextColumn::make('id')->label('ID')->sortable(),
                TextColumn::make('name')->label('Tiêu đề')->limit(30),
                TextColumn::make('content')
                    ->label('Nội dung')
                    ->limit(50)
                    ->html(),
                TextColumn::make('user_notifications_count')->label('Số người nhận'),
                TextColumn::make('notification_type')->label('Loại thông báo')->sortable(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    EditAction::make()
                        ->color('warning'),

                    ViewAction::make(),
                    DeleteAction::make(),
                    RestoreAction::make(),
                ]),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->modifyQueryUsing(fn(Builder $query) => $query->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]));
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withCount('userNotifications');
    }

    public static function getRelations(): array
    {
        return [
            UserRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNotifications::route('/'),
            'create' => Pages\CreateNotification::route('/create'),
            'edit' => Pages\EditNotification::route('/{record}/edit'),
        ];
    }
}
