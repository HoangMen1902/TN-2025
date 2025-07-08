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

            Select::make('voucher_id')
                ->label('Voucher tặng kèm khi đạt hạng')
                ->relationship(
                    name: 'voucher',
                    titleAttribute: 'voucher_name',
                    modifyQueryUsing: fn($query) => $query->where('issued_by', 'membership'),
                )
                ->getOptionLabelFromRecordUsing(fn($record) => "{$record->voucher_name}")
                ->searchable()
                ->preload()
                ->nullable()

                ->createOptionForm([
                    TextInput::make('voucher_code')
                        ->label('Mã voucher')
                        ->required()
                        ->unique(table: Voucher::class, column: 'voucher_code'),

                    TextInput::make('voucher_name')
                        ->label('Tên hiển thị')
                        ->required(),

                    TextInput::make('requirement_price')
                        ->label('Giá trị đơn hàng tối thiểu')
                        ->numeric()
                        ->minValue(0)
                        ->required(),

                    Select::make('voucher_type')
                        ->label('Loại giảm giá')
                        ->options([
                            'percent' => 'Phần trăm',
                            'amount' => 'Cố định',
                        ])
                        ->required()
                        ->reactive(),

                    TextInput::make('reduced_amount')
                        ->label(fn($get) => $get('voucher_type') === 'percent' ? 'Phần trăm giảm (%)' : 'Số tiền giảm (VNĐ)')
                        ->numeric()
                        ->minValue(fn($get) => $get('voucher_type') === 'percent' ? 1 : 1000)
                        ->maxValue(fn($get) => $get('voucher_type') === 'percent' ? 100 : null)
                        ->required(),

                    TextInput::make('max_discount_amount')
                        ->label('Giảm tối đa (nếu là %)')
                        ->numeric()
                        ->minValue(0)
                        ->visible(fn($get) => $get('voucher_type') === 'percent'),

                    TextInput::make('quantity')
                        ->label('Tổng số lượng')
                        ->numeric()
                        ->minValue(1)
                        ->nullable(),

                    TextInput::make('usage_per_user')
                        ->label('Số lần mỗi người dùng được dùng')
                        ->numeric()
                        ->minValue(1)
                        ->nullable(),

                    DateTimePicker::make('start_at')
                        ->label('Bắt đầu')
                        ->nullable(),

                    DateTimePicker::make('expired_at')
                        ->label('Hết hạn')
                        ->rules(['required', 'after:now']),

                    Select::make('voucher_status')
                        ->label('Trạng thái')
                        ->options([
                            'active' => 'Hoạt động',
                            'inactive' => 'Không hoạt động',
                        ])
                        ->required(),
                    Select::make('issued_by')
                        ->label('Nguồn phát hành')

                        ->options([
                            'manual' => 'Tạo thủ công',
                            'membership' => 'Thăng hạng thành viên',
                            'point' => 'Đổi điểm',
                        ])
                        ->default('membership')
                        ->rules([
                            'required',
                            'in:manual,membership,point',
                        ])
                        ->validationMessages([
                            'required' => 'Vui lòng chọn nguồn phát hành.',
                            'in' => 'Giá trị không hợp lệ. Chỉ chấp nhận: Tạo thủ công, Thăng hạng, hoặc Đổi điểm.',
                        ]),


                    Toggle::make('is_redeemable')
                        ->label('Cho phép đổi bằng điểm')
                        ->default(false)
                        ->reactive(),

                    TextInput::make('required_points')
                        ->label('Điểm cần để đổi')
                        ->numeric()
                        ->minValue(1)
                        ->visible(fn($get) => $get('is_redeemable'))
                        ->rules(fn($get) => $get('is_redeemable') ? ['required', 'numeric', 'min:1'] : ['nullable']),
                ])

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

            TextColumn::make('voucher.voucher_name')
                ->label('Voucher tặng')
                ->default('—'),

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
