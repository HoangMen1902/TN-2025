<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VoucherResource\Pages;
use App\Filament\Resources\VoucherResource\RelationManagers;
use App\Models\Voucher;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Card;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\SoftDeletes;
use Filament\Forms\Components\Toggle;

class VoucherResource extends Resource
{
    use SoftDeletes;
    protected static ?string $model = Voucher::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationGroup = 'Chương trình giảm giá';
    protected static ?string $navigationLabel = 'Mã giảm giá';
    protected static ?string $modelLabel = 'Mã giảm giá';
    protected static ?string $pluralModelLabel = 'Các Mã giảm giá';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    public static function form(Form $form): Form
    {
        return $form->schema([
            Card::make()
                ->schema([
                    TextInput::make('voucher_code')
                        ->label('Mã voucher')
                        ->required()
                        ->maxLength(50)
                        ->unique(ignoreRecord: true)
                        ->validationMessages([
                            'required' => 'Vui lòng nhập mã voucher',
                            'unique' => 'Mã voucher đã tồn tại',
                            'max' => 'Mã không được vượt quá 50 ký tự',
                        ]),

                    TextInput::make('voucher_name')
                        ->label('Tên hiển thị')
                        ->rules(['required', 'max:255'])
                        ->validationMessages([
                            'required' => 'Vui lòng nhập tên voucher',
                            'max' => 'Tên voucher không được vượt quá 255 ký tự',
                        ]),

                    TextInput::make('requirement_price')
                        ->label('Giá trị đơn hàng tối thiểu (đ)')
                        ->numeric()
                        ->minValue(0)
                        ->rules(['required', 'numeric'])
                        ->validationMessages([
                            'required' => 'Vui lòng nhập giá trị tối thiểu',
                            'numeric' => 'Phải là số',
                        ]),

                    Select::make('voucher_type')
                        ->label('Loại giảm giá')
                        ->options([
                            'percent' => 'Phần trăm',
                            'amount' => 'Cố định',
                        ])
                        ->rules(['required'])
                        ->validationMessages([
                            'required' => 'Vui lòng chọn loại voucher',
                        ])
                        ->reactive(),

                    TextInput::make('reduced_amount')
                        ->label(
                            fn($get) => $get('voucher_type') === 'percent'
                                ? 'Phần trăm giảm (%)'
                                : 'Số tiền giảm (đ)'
                        )
                        ->numeric()
                        ->minValue(fn($get) => $get('voucher_type') === 'percent' ? 1 : 1000)
                        ->maxValue(fn($get) => $get('voucher_type') === 'percent' ? 100 : null)
                        ->rules(['required'])
                        ->validationMessages([
                            'required' => 'Vui lòng nhập giá trị giảm',
                        ]),

                    TextInput::make('max_discount_amount')
                        ->label('Giảm tối đa (chỉ áp dụng nếu là %)')
                        ->numeric()
                        ->minValue(0)
                        ->visible(fn($get) => $get('voucher_type') === 'percent'),

                    TextInput::make('quantity')
                        ->label('Tổng số lượng')
                        ->numeric()
                        ->minValue(1)
                        ->nullable(),

                    Hidden::make('usage_per_user')
                        ->label('Số lần mỗi người dùng được dùng')
                        ->default(1)
                        ->dehydrateStateUsing(fn($state) => $state ?? 1),


                    Select::make('voucher_scope')
                        ->label('Phạm vi áp dụng')
                        ->options([
                            'global' => 'Toàn sàn',
                            'shipping' => 'Miễn phí vận chuyển',
                        ])
                        ->default('global'),

                    DateTimePicker::make('start_at')
                        ->label('Thời gian bắt đầu')
                        ->nullable(),

                    DateTimePicker::make('expired_at')
                        ->label('Thời gian hết hạn')
                        ->rules(['required', 'after:now'])
                        ->validationMessages([
                            'required' => 'Vui lòng chọn thời gian hết hạn',
                            'after' => 'Phải sau thời điểm hiện tại',
                        ]),

                    Select::make('voucher_status')
                        ->label('Trạng thái')
                        ->options([
                            'active' => 'Hoạt động',
                            'inactive' => 'Không hoạt động',
                        ])
                        ->rules(['required'])
                        ->validationMessages([
                            'required' => 'Vui lòng chọn trạng thái voucher',
                        ]),

                    Select::make('issued_by')
                        ->label('Nguồn phát hành')
                        ->options([
                            'manual' => 'Tạo thủ công',
                            'membership' => 'Thăng hạng thành viên',
                            'point' => 'Đổi điểm',
                        ])
                        ->default('manual')
                        ->rules([
                            'required',
                            'in:manual,membership,point',
                        ])
                        ->validationMessages([
                            'required' => 'Vui lòng chọn nguồn phát hành.',
                            'in' => 'Giá trị không hợp lệ. Chỉ chấp nhận: Tạo thủ công, Thăng hạng, hoặc Đổi điểm.',
                        ]),
                    Select::make('membership_id')
                        ->label('Áp dụng cho hạng thành viên')
                        ->relationship('membership', 'name')
                        ->nullable()
                        ->searchable()
                        ->preload()
                        ->helperText('Chỉ áp dụng cho thành viên thuộc hạng này (nếu có)')
                        ->validationMessages([
                            'exists' => 'Hạng thành viên không tồn tại',
                        ])->columnSpan(1),
                    Toggle::make('is_redeemable')
                        ->label('Cho phép đổi bằng điểm')
                        ->default(false)
                        ->inline(false)
                        ->reactive(),

                    TextInput::make('required_points')
                        ->label('Số điểm cần để đổi')
                        ->numeric()
                        ->minValue(1)
                        ->visible(fn($get) => $get('is_redeemable'))
                        ->rules(function (callable $get) {
                            return $get('is_redeemable')
                                ? ['required', 'numeric', 'min:1']
                                : ['nullable'];
                        })->columnSpan(2)
                        ->validationMessages([
                            'required' => 'Vui lòng nhập số điểm để đổi voucher',
                            'numeric' => 'Phải là số',
                            'min' => 'Tối thiểu là 1 điểm',
                        ]),


                ])
                ->columns(2)

        ]);
    }


    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('voucher_code')
                ->label('Mã voucher')
                ->searchable()
                ->sortable(),

            TextColumn::make('voucher_scope')
                ->label('Phạm vi áp dụng')
                ->formatStateUsing(fn($state) => match ($state) {
                    'global' => 'Toàn sàn',
                    'shipping' => 'Miễn phí vận chuyển',
                    default => ucfirst($state),
                })
                ->searchable()
                ->sortable(),


            TextColumn::make('voucher_type')
                ->label('Loại')
                ->formatStateUsing(fn($state) => match ($state) {
                    'percent' => 'Phần trăm',
                    'amount' => 'Cố định',
                    default => ucfirst($state),
                })
                ->sortable(),

            TextColumn::make('reduced_amount')
                ->label('Giảm')
                ->sortable()
                ->formatStateUsing(function ($state, $record) {
                    return $record->voucher_type === 'percent'
                        ? $state . ' %'
                        : number_format($state, 0, ',', '.') . ' đ';
                }),
            TextColumn::make('is_redeemable')
                ->label('Có thể đổi điểm')
                ->badge()
                ->formatStateUsing(fn($state) => $state ? 'Có' : 'Không')
                ->color(fn($state) => $state ? 'success' : 'gray')
                ->sortable(),

            TextColumn::make('required_points')
                ->label('Điểm cần để đổi')
                ->sortable()
                ->formatStateUsing(
                    fn($state, $record) =>
                    $record->is_redeemable && $state
                        ? number_format($state) . ' điểm'
                        : '—'
                ),
            TextColumn::make('issued_by')
                ->label('Nguồn phát hành')
                ->sortable()
                ->badge()
                ->formatStateUsing(fn($state) => match ($state) {
                    'manual' => 'Tạo thủ công',
                    'membership' => 'Thăng hạng',
                    'point' => 'Đổi điểm',
                    default => '—',
                })
                ->color(fn($state) => match ($state) {
                    'manual' => 'gray',
                    'membership' => 'info',
                    'point' => 'success',
                    default => 'secondary',
                }),


            TextColumn::make('expired_at')
                ->label('Hết hạn')
                ->dateTime()
                ->sortable(),

            TextColumn::make('voucher_status')
                ->label('Trạng thái')
                ->badge()
                ->formatStateUsing(fn($state) => $state === 'active' ? 'Hoạt động' : 'Khóa')
                ->color(fn($state) => $state === 'active' ? 'success' : 'danger'),
        ])
            ->filters([
                // Có thể thêm lọc theo trạng thái, loại, phạm vi...
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Chỉnh sửa'),
                Tables\Actions\DeleteAction::make()->label('Xoá'),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()->label('Xoá hàng loạt'),
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
            'index' => Pages\ListVouchers::route('/'),
            'create' => Pages\CreateVoucher::route('/create'),
            'edit' => Pages\EditVoucher::route('/{record}/edit'),
        ];
    }
}
