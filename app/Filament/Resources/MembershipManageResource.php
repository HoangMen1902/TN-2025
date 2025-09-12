<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MembershipManageResource\Pages;
use App\Models\User;
use App\Models\UserPoint;
use App\Models\PointTransaction;
use App\Models\Membership;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Filament\Notifications\Notification;
use Illuminate\Support\Carbon;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Actions\ActionGroup;
use Filament\Forms\Components\Card;

class MembershipManageResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'Quản lý Hội viên';
    protected static ?string $modelLabel = 'Hội viên';
    protected static ?string $pluralModelLabel = 'Danh sách Hội viên';
    protected static ?string $navigationGroup = 'Quản lý Người Dùng';
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    public static function table(Table $table): Table
    {
        return $table
            ->defaultPaginationPageOption(50)
            ->columns([
                TextColumn::make('name')->label('Tên'),
                TextColumn::make('email')->label('Email'),
                TextColumn::make('membership.name')->label('Hạng'),
                TextColumn::make('point.total_points')->label('Tổng điểm')->sortable(),
                TextColumn::make('point.redeemable_points')->label('Điểm khả dụng')->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('membership_id')
                    ->label('Lọc theo hạng hội viên')
                    ->relationship('membership', 'name'),

                Tables\Filters\SelectFilter::make('membership_status')
                    ->label('Trạng thái hội viên')
                    ->default('member')
                    ->options([
                        'member' => 'Đã là hội viên',
                        'non_member' => 'Chưa là hội viên',
                    ])
                    ->query(function (Builder $query, array $data) {
                        if ($data['value'] === 'member') {
                            return $query->whereNotNull('membership_id');
                        } elseif ($data['value'] === 'non_member') {
                            return $query->whereNull('membership_id');
                        }

                        return $query;
                    }),


                Tables\Filters\Filter::make('min_points')
                    ->label('Tối thiểu bao nhiêu điểm')
                    ->form([
                        Forms\Components\TextInput::make('value')
                            ->label('Tối thiểu')
                            ->numeric(),
                    ])
                    ->query(function (Builder $query, array $data) {
                        if (!empty($data['value'])) {
                            return $query->whereHas('point', function ($q) use ($data) {
                                $q->where('total_points', '>=', $data['value']);
                            });
                        }

                        return $query;
                    }),
            ])

            ->actions([
                ActionGroup::make([
                    Action::make('add_point')
                        ->label('Cộng điểm')
                        ->icon('heroicon-o-plus-circle')
                        ->form([
                            TextInput::make('points')
                                ->label('Số điểm')
                                ->numeric()
                                ->rules(['required', 'numeric', 'min:1'])
                                ->validationMessages([
                                    'required' => 'Vui lòng nhập số điểm.',
                                    'numeric' => 'Số điểm phải là số.',
                                    'min' => 'Số điểm tối thiểu là 1.',
                                ]),
                            Textarea::make('source')
                                ->label('Ghi chú')
                                ->rules(['required', 'string'])
                                ->validationMessages([
                                    'required' => 'Vui lòng nhập ghi chú.',
                                    'string' => 'Ghi chú phải là chuỗi văn bản.',
                                ])
                        ])

                        ->action(function (User $record, array $data) {
                            if (!$record->point) {
                                $record->point()->create([
                                    'total_points' => 0,
                                    'redeemable_points' => 0,
                                ]);


                                $record->load('point');
                            }

                            // Cộng điểm
                            $record->point->increment('total_points', $data['points']);
                            $record->point->increment('redeemable_points', $data['points']);

                            // Ghi log
                            PointTransaction::create([
                                'user_id' => $record->id,
                                'points' => $data['points'],
                                'type' => 'earn',
                                'source' => $data['source'],
                            ]);

                            Notification::make()
                                ->title('Đã cộng điểm thành công')
                                ->success()
                                ->send();
                        }),



                    Action::make('subtract_point')
                        ->label('Trừ điểm')
                        ->icon('heroicon-o-minus-circle')
                        ->form([
                            TextInput::make('points')
                                ->label('Số điểm')
                                ->numeric()
                                ->rules(['required', 'numeric', 'min:1'])
                                ->validationMessages([
                                    'required' => 'Vui lòng nhập số điểm.',
                                    'numeric' => 'Số điểm phải là số.',
                                    'min' => 'Số điểm tối thiểu là 1.',
                                ]),
                            Textarea::make('source')
                                ->label('Ghi chú')
                                ->rules(['required', 'string'])
                                ->validationMessages([
                                    'required' => 'Vui lòng nhập ghi chú.',
                                    'string' => 'Ghi chú phải là chuỗi văn bản.',
                                ])
                        ])
                        ->action(function (User $record, array $data) {
                            if (!$record->point) {
                                $record->point()->create([
                                    'total_points' => 0,
                                    'redeemable_points' => 0,
                                ]);


                                $record->load('point');
                            }
                            $points = $data['points'];


                            if ($record->point->redeemable_points < $points) {
                                Notification::make()
                                    ->title('Không đủ điểm để trừ')
                                    ->danger()
                                    ->send();
                                return;
                            }

                            $record->point->decrement('redeemable_points', $points);

                            PointTransaction::create([
                                'user_id' => $record->id,
                                'points' => $points,
                                'type' => 'redeem',
                                'source' => $data['source'],
                            ]);

                            Notification::make()
                                ->title('Đã trừ điểm thành công')
                                ->success()
                                ->send();
                            if (!$record->point || $record->point->redeemable_points < $points) {
                                Notification::make()
                                    ->title('Không đủ điểm để trừ')
                                    ->danger()
                                    ->send();
                                return;
                            }
                        }),

                    Action::make('view_log')
                        ->label('Xem lịch sử điểm')
                        ->icon('heroicon-o-clock')
                        ->modalHeading('Lịch sử điểm của hội viên')
                        ->modalSubmitAction(false)
                        ->modalCancelActionLabel('Đóng')
                        ->form(function (Model $record) {
                            $logs = $record->pointTransactions()
                                ->latest()
                                ->get()
                                ->map(function ($log, $index) {
                                    return [
                                        'stt' => $index + 1,
                                        'time' => Carbon::parse($log->created_at)->format('d/m/Y H:i'),
                                        'points' => $log->points,
                                        'type' => $log->type === 'earn' ? 'Cộng' : 'Trừ',
                                        'source' => $log->source,
                                    ];
                                });

                            if ($logs->isEmpty()) {
                                return [
                                    Placeholder::make('empty')->content('Chưa có lịch sử điểm nào.'),
                                ];
                            }

                            return [
                                Section::make('Lịch sử gần nhất')
                                    ->schema($logs->map(function ($log) {
                                        return Card::make([
                                            Grid::make(4)->schema([
                                                Placeholder::make('time')
                                                    ->label('Thời gian')
                                                    ->content($log['time']),
                                                Placeholder::make('points')
                                                    ->label('Điểm')
                                                    ->content($log['type'] === 'Cộng' ? "+{$log['points']}" : "-{$log['points']}"),
                                                Placeholder::make('type')
                                                    ->label('Loại')
                                                    ->content($log['type']),
                                                Placeholder::make('source')
                                                    ->label('Ghi chú')
                                                    ->content($log['source'] ?? 'Không có ghi chú'),
                                            ]),
                                        ])->extraAttributes([
                                            'class' => 'border rounded-lg p-4 shadow-sm bg-white',
                                        ]);
                                    })->toArray())
                                    ->columns(1),
                            ];
                        }),
                ])
                    ->label('Điểm hội viên')
                    ->iconButton()
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMembershipManages::route('/'),
            'edit' => Pages\EditMembershipManage::route('/{record}/edit'),
        ];
    }
}
