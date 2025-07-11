<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
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
use Illuminate\Database\Eloquent\Model;
use App\Models\Membership;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationGroup = 'Quản lý Người Dùng';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static ?string $navigationLabel = 'Người Dùng';
    protected static ?string $modelLabel = 'Người Dùng';
    protected static ?string $pluralModelLabel = 'Các Người Dùng';

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
                ->nullable()
                ->before(now()->subYears(16))
                ->rule('date')
                ->rule('before:' . now()->subYears(16)->toDateString())
                ->helperText('Người dùng phải trên 16 tuổi'),

            TextInput::make('avatar')
                ->label('Ảnh đại diện (URL)')
                ->nullable(),



            Forms\Components\Select::make('roles')
                ->relationship('roles', 'name')
                ->saveRelationshipsUsing(function (Model $record, $state) {
                    $record->roles()->sync($state);
                })
                ->multiple()
                ->preload()
                ->searchable(),

            Toggle::make('publisher_status')
                ->label('Kích hoạt')
                ->default(true)
                ->columnSpan(2),
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
                TextColumn::make('user_status')
                    ->label('Trạng thái')
                    ->badge()
                    ->formatStateUsing(fn($state) => $state === 'active' ? 'Hoạt động' : 'Khóa')
                    ->color(fn($state) => $state === 'active' ? 'success' : 'danger'),
                TextColumn::make('roles.name')
                    ->label('Vai trò')
                    ->badge()
                    ->formatStateUsing(fn($state) => is_array($state) ? implode(', ', $state) : $state)
                    ->color('primary'),


            ])
            ->filters([
                Tables\Filters\TrashedFilter::make()->default('with'),
                SelectFilter::make('role')
                    ->label('Lọc theo vai trò')
                    ->options([
                        'admin' => 'Quản trị',
                        'user' => 'Khách hàng',
                    ])
                    ->default(null),
                SelectFilter::make('user_status')
                    ->label('Lọc theo trạng thái')
                    ->options([
                        'active' => 'Hoạt động',
                        'inactive' => 'Khóa',
                    ])
                    ->default(null),
            ])
            ->actions([
                Action::make('viewMembershipRank')
                    ->label('Hội viên')
                    ->icon('heroicon-o-identification')
                    ->modalHeading('Thông tin hội viên')
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Đóng')
                    ->form(function (Model $record) {
                        $totalPoints = $record->point->total_points ?? 0;
                        $membershipName = $record->membership->name ?? 'Chưa có';

                        $nextMembership = Membership::where('required_points', '>', $totalPoints)
                            ->orderBy('required_points')
                            ->first();

                        $nextMembershipContent = $nextMembership
                            ? $nextMembership->name . ' (Cần thêm ' . ($nextMembership->required_points - $totalPoints) . ' điểm)'
                            : 'Đã đạt hạng cao nhất';

                        return [
                            Section::make('Thông tin hội viên')
                                ->schema([
                                    Grid::make(2)
                                        ->schema([
                                            Placeholder::make('membership')
                                                ->label('Hạng hiện tại')
                                                ->content($membershipName),

                                            Placeholder::make('total_points')
                                                ->label('Tổng điểm')
                                                ->content($totalPoints),

                                            Placeholder::make('next_rank')
                                                ->label('Hạng tiếp theo')
                                                ->columnSpan(2)
                                                ->content($nextMembershipContent),
                                        ]),
                                ])
                                ->columns(1),
                        ];
                    }),
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make()
                        ->label('Chỉnh sửa ')
                        ->color('warning')
                        ->action(function ($record) {
                            activity()
                                ->causedBy(Auth::user())
                                ->performedOn($record)
                                ->log('Cập nhật thông tin người dùng: ' . $record->name);
                        }),
                    Tables\Actions\DeleteAction::make()
                        ->label('Vô hiệu hóa')
                        ->color('danger')
                        ->action(function ($record) {
                            if ($record->id === Auth::id()) {
                                Notification::make()
                                    ->title('Không thể xóa tài khoản đang sử dụng.')
                                    ->danger()
                                    ->send();
                                return;
                            }
                            $record->user_status = 'inactive';
                            $record->save();
                            $record->delete();

                            activity()
                                ->causedBy(Auth::user())
                                ->performedOn($record)
                                ->log('Xóa người dùng: ' . $record->name);
                        }),
                    Tables\Actions\RestoreAction::make()->action(function ($record) {
                        $record->user_status = 'active';
                        $record->save();
                        $record->restore();

                        activity()
                            ->causedBy(Auth::user())
                            ->performedOn($record)
                            ->log('Khôi phục người dùng: ' . $record->name);
                    }),
                    Tables\Actions\ViewAction::make()
                        ->label('xem chi tiết'),

                ]),

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->before(function ($records, $action) {
                            foreach ($records as $record) {
                                if ($record->orders()->exists()) {
                                    Notification::make()
                                        ->title("Không thể xóa vĩnh viễn tài khoản ID {$record->id} đã từng mua hàng.")
                                        ->danger()
                                        ->send();
                                  
                                    $action->cancel();
                                    return;
                                }
                            }
                        }),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->modifyQueryUsing(fn(Builder $query) => $query->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]));
    }

    public static function getRelations(): array
    {
        return [
            \App\Filament\Resources\UserResource\RelationManagers\ActivitiesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'view' => Pages\ViewUser::route('/{record}'),
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
