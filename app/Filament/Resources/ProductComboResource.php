<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductComboResource\Pages;
use App\Models\ProductCombo;
use App\Models\comboSku;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Placeholder;
use Illuminate\Support\HtmlString;
use App\Models\Category;
use App\Models\ProductSku;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Actions\ForceDeleteAction;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use Filament\Forms\Components\DateTimePicker;

class ProductComboResource extends Resource
{
    protected static ?string $model = ProductCombo::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Quản lý Sản phẩm';
    protected static ?string $navigationLabel = 'Combo sản phẩm';
    protected static ?string $pluralModelLabel = 'Combo sản phẩm';
    protected static ?string $modelLabel = 'Combo sản phẩm';
    protected static ?int $navigationSort = 3;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    public static function form(Form $form): Form
    {
        return $form->schema([
            Grid::make(4)->schema([
                Grid::make(2)->schema([
                    TextInput::make('combo_name')
                        ->label('Tên Combo')
                        ->rules('required')
                        ->live(debounce: 1000) 
                        ->afterStateUpdated(function ($state, callable $set, callable $get) {
                            $set('slug', Str::slug($state));
                        })
                        ->validationMessages(['required' => 'Vui lòng nhập tên combo'])
                        ->unique(ignoreRecord: true),

                    TextInput::make('slug')
                        ->label('Đường dẫn')
                        ->rules('required')
                        ->validationMessages(['required' => 'Vui lòng nhập đường dẫn'])
                        ->unique(ignoreRecord: true),
                ])->columnSpan(4),
                RichEditor::make('description')
                    ->label('Mô tả combo')
                    ->columnSpanFull()
                    ->validationMessages([
                        'required' => 'Vui lòng nhập thông tin này.',
                    ]),
                TextInput::make('width')->label('Chiều rộng (cm)')->numeric()->rules(['required'])->validationMessages(['required' => 'Vui lòng nhập thông tin này']),
                TextInput::make('length')->label('Chiều dài (cm)')->numeric()->rules(['required'])->validationMessages(['required' => 'Vui lòng nhập thông tin này']),
                TextInput::make('height')->label('Chiều cao (cm)')->numeric()->rules(['required'])->validationMessages(['required' => 'Vui lòng nhập thông tin này']),
                TextInput::make('weight')->label('Cân nặng (g)')->numeric()->rules(['required'])->validationMessages(['required' => 'Vui lòng nhập thông tin này']),
                FileUpload::make('images')
                    ->label('Ảnh sản phẩm')
                    ->required()
                    ->image()
                    ->multiple()
                    ->maxFiles(10)
                    ->columnSpan(4)
                    ->validationMessages([
                        'required' => 'Vui lòng tải lên ít nhất 1 ảnh combo',
                    ]),
                TextInput::make('original_price')
                    ->label('Giá gốc')
                    ->numeric()
                    ->rules(['required', 'min:0'])
                    ->validationMessages([
                        'required' => 'Vui lòng nhập thông tin này.',
                        'min' => 'Giá không được nhỏ hơn 0.',
                    ]),


                TextInput::make('sale_price')
                    ->label('Giá khuyến mãi')
                    ->numeric()
                    ->minValue(0)
                    ->rules(['nullable', 'min:0', 'required'])
                    ->validationMessages([
                        'required' => 'Vui lòng nhập thông tin này.',
                        'min' => 'Giá khuyến mãi không được nhỏ hơn 0.',
                    ]),
                DateTimePicker::make('expired_at')
                    ->label('Ngày hết hạn')
                    ->displayFormat('d/m/Y H:i')
                    ->minDate(now())
                    ->rules(['required'])
                    ->validationMessages([
                        'required' => 'Vui lòng chọn ngày hết hạn.',
                    ])
                    ->columnSpan(1),



                TextInput::make('quantity')
                    ->label('Số lượng Combo')
                    ->numeric()
                    ->rules(['required', 'integer', 'min:1'])
                    ->validationMessages([
                        'required' => 'Vui lòng nhập số lượng combo.',
                        'integer' => 'Số lượng phải là số nguyên.',
                        'min' => 'Số lượng combo phải lớn hơn hoặc bằng 1.',
                    ])
                    ->columnSpan(1),

                Select::make('product_combos_status')
                    ->label('Trạng thái')
                    ->default('active')
                    ->options([
                        'active' => 'Hoạt động',
                        'intactive' => 'Khóa'
                    ])
                    ->formatStateUsing(fn($state) => $state ? 'active' : 'inactive')
                    ->dehydrateStateUsing(fn($state) => $state ? 'active' : 'inactive')
                    ->rules(['required'])
                    ->validationMessages(['required' => 'Vui lòng chọn trạng thái *']),
                Select::make('category_filter')
                    ->label('Lọc theo loại sản phẩm')
                    ->options(fn() => Category::pluck('name', 'id'))
                    ->reactive()
                    ->afterStateUpdated(fn($state, callable $set) => $set('combo_items', static::getFilteredSkus($state)))
                    ->columnSpan(4),


                Repeater::make('combo_items')
                    ->schema([
                        Checkbox::make('selected')->label('Chọn'),

                        Hidden::make('sku_id'),
                        Hidden::make('productName'),
                        // Hidden::make('quantity'),
                        Hidden::make('image_url'),

                        Placeholder::make('Tên sản phẩm')
                            ->content(fn($get) => $get('productName')),
                        Placeholder::make('Giá sản phẩm')
                            ->content(fn($get) => $get('price')),
                        Placeholder::make('Số lượng còn')
                            ->content(fn($get) => $get('quantity')),

                        // TextInput::make('sku_quantity')
                        //     ->label('Số lượng trong combo')
                        //     ->numeric()
                        //     ->default(1)
                        //     ->minValue(1)                         ,

                        Placeholder::make('Ảnh')
                            ->content(function ($get) {
                                $images = $get('image_url');
                                if (is_array($images) && count($images) > 0) {
                                    $url = asset('storage/' . ltrim($images[0], '/'));
                                    return new HtmlString("<img src='{$url}' width='80' />");
                                }
                                return 'Không có ảnh';
                            }),
                    ])
                    ->columns(5)
                    ->default(fn() => static::getFilteredSkus(null))
                    ->label('Danh sách SKU')
                    ->hiddenLabel()
                    ->columnSpanFull()

            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('ID'),
                TextColumn::make('combo_name')->label('Tên combo')->html()->limit(50)->searchable(),
                TextColumn::make('original_price')->label('Giá gốc')->money('VND'),
                TextColumn::make('sale_price')->label('Giá Sale')->money('VND')->sortable(),
                TextColumn::make('expired_at')->label('Thời gian hết hạn')->sortable(),
                TextColumn::make('product_combos_status')->label('Trạng thái')->badge()->formatStateUsing(function ($state) {
                    return match ($state) {
                        'active' => 'Hoạt động',
                        'inactive' => 'Khóa',
                        default => 'Không xác định'
                    };
                })->color(fn($state) => $state === 'active' ? 'success' : 'danger')->searchable(),
            ])->filters([
                SelectFilter::make('product_combos_status')
                    ->label('Lọc theo trạng thái')
                    ->options([
                        'active' => 'Hoạt động',
                        'inactive' => 'Khóa',
                    ]),

            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->action(function ($record) {
                        $record->delete();
                        activity()
                            ->causedBy(Auth::user())
                            ->performedOn($record)
                            ->log('Xóa combo: ' . $record->combo_name);
                    }),
                RestoreAction::make()
                    ->action(function ($record) {
                        $record->restore();
                        activity()
                            ->causedBy(Auth::user())
                            ->performedOn($record)
                            ->log('Khôi phục combo: ' . $record->combo_name);
                    }),
                ForceDeleteAction::make()
                    ->action(function ($record) {
                        $record->forceDelete();
                        activity()
                            ->causedBy(Auth::user())
                            ->performedOn($record)
                            ->log('Xóa vĩnh viễn combo: ' . $record->combo_name);
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $selectedItems = collect($data['combo_items'] ?? [])
            ->where('selected', true);

        if ($selectedItems->count() < 2) {
            throw ValidationException::withMessages([
                'combo_items' => 'Bạn phải chọn ít nhất 2 sản phẩm cho combo.',
            ]);
        }

        return $data;
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProductCombos::route('/'),
            'create' => Pages\CreateProductCombo::route('/create'),
            'edit' => Pages\EditProductCombo::route('/{record}/edit'),
        ];
    }

    public static function getFilteredSkus($categoryId = null): array
    {
        $skus = $categoryId
            ? ProductSku::with('product')
            ->whereHas('product', function ($q) use ($categoryId) {
                $q->whereNull('deleted_at')
                    ->whereHas('categories', function ($q2) use ($categoryId) {
                        $q2->where('categories.id', $categoryId);
                    });
            })
            ->get()
            : ProductSku::with('product')
            ->whereHas('product', function ($q) {
                $q->whereNull('deleted_at');
            })
            ->get();
        return $skus->map(function ($sku) {
            return [
                'selected' => false,
                'sku_id' => $sku->id,
                'productName' => isset($sku->product->name) ? $sku->product->name . ' - ' . $sku->sku : 'Undefined',
                'quantity' => $sku->quantity,
                'sku_quantity' => 1,
                'image_url' => is_string($sku->images) ? json_decode($sku->images, true) : ($sku->images ?? []),
            ];
        })->toArray();
    }

    public static function afterUpdate(Model $record, array $data): void
    {
        $record->comboSkus()->delete();
        foreach ($data['combo_items'] ?? [] as $item) {
            if (!empty($item['selected']) && !empty($item['sku_id'])) {
                ComboSku::create([
                    'combo_id' => $record->id,
                    'sku_id' => $item['sku_id'],
                    'quantity' => $item['sku_quantity'] ?? 1,
                ]);
            }
        }
    }

    public static function afterCreate(Model $record, array $data): void
    {
        foreach ($data['combo_items'] ?? [] as $item) {
            if (!empty($item['selected']) && !empty($item['sku_id'])) {
                ComboSku::create([
                    'combo_id' => $record->id,
                    'sku_id' => $item['sku_id'],
                    'quantity' => $item['sku_quantity'] ?? 1,
                ]);
            }
        }
    }
}


// class ProductComboResource extends Resource
// {
//     protected static ?string $model = ProductCombo::class;

//     protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
//     protected static ?string $navigationGroup = 'Quản lý Sản phẩm';
//     protected static ?string $navigationLabel = 'Combo';
//     protected static ?string $pluralModelLabel = 'Combo';
//     protected static ?string $modelLabel = 'Combo';

//     public static function form(Form $form): Form
//     {
//         return $form->schema([
//             Grid::make(4)->schema([
//                 Textarea::make('description')
//                     ->label('Mô tả combo')
//                     ->rows(3)
//                     ->columnSpan(4),

//                 TextInput::make('original_price')
//                     ->label('Giá gốc')
//                     ->numeric()
//                     ->columnSpan(1),

//                 TextInput::make('sale_price')
//                     ->label('Giá giảm')
//                     ->numeric()
//                     ->columnSpan(1),

//                 // TextInput::make('quantity')
//                 //     ->label('Số lượng combo')
//                 //     ->numeric()
//                 //     ->default(1)
//                 //     ->columnSpan(1),

//                 DatePicker::make('expired_at')
//                     ->label('Ngày hết hạn')
//                     ->columnSpan(1),

//                 Select::make('category_filter')
//                     ->label('Lọc theo loại sản phẩm')
//                     ->options(Category::all()->pluck('name', 'id'))
//                     ->reactive()
//                     ->afterStateUpdated(fn($state, callable $set) => $set('combo_items', static::getFilteredSkus($state)))
//                     ->columnSpan(2),
//                     // TextInput::make('quantityCombo')
//                     // ->label('Số lượng trong combo')
//                     // ->numeric()
//                     // ->default(1),
//                    TextInput::make('quantityCombo')
//                    ->label('Số lượng Combo')
//                    ->numeric()
//                    ->required()
//                    ->rules([
//                        function (string $attribute, $value, $fail) {
//                            // Lưu lại Closure để bên ngoài vẫn truy cập được $get
//                            return function (callable $get) use ($attribute, $value, $fail) {
//                                if ($value > $get('quantity')) {
//                                    $fail('Số lượng combo không được lớn hơn số lượng.');
//                                }
//                            };
//                        }
//                    ]),
//                 Repeater::make('combo_items')
//                     ->schema([
//                         Checkbox::make('selected')->label('Chọn'),

//                         Hidden::make('sku_id'),
//                         Hidden::make('productName'),
//                         Hidden::make('quantity'),
//                         Hidden::make('image_url'),

//                         Placeholder::make('Tên sản phẩm')
//                             ->content(fn($get) => $get('productName')),
//                         Placeholder::make('Số lượng còn')
//                             ->content(fn($get) => $get('quantity')),

//                         // TextInput::make('sku_quantity')
//                         //     ->label('Số lượng trong combo')
//                         //     ->numeric()
//                         //     ->default(1),

//                         Placeholder::make('Ảnh')
//                             ->content(function ($get) {
//                                 $images = $get('image_url');
//                                 if (is_array($images) && count($images) > 0) {
//                                     $url = asset('storage/' . ltrim($images[0], '/'));
//                                     return new HtmlString("<img src='{$url}' width='80' />");
//                                 }
//                                 return 'Không có ảnh';
//                             }),
//                     ])
//                     ->columns(4)
//                     ->default(fn() => static::getFilteredSkus(null))
//                     ->label('Danh sách SKU')
//                     ->hiddenLabel()
//                     ->columnSpanFull(),
//             ]),
//         ]);
//     }
 
//     public static function table(Table $table): Table
//     {
//         return $table
//             ->columns([
//                 TextColumn::make('id')->label('ID'),
//                 TextColumn::make('description')->label('Mô tả'),
//             ])
//             ->actions([
//                 Tables\Actions\EditAction::make(),
//             ])
//             ->bulkActions([
//                 Tables\Actions\BulkActionGroup::make([
//                     Tables\Actions\DeleteBulkAction::make(),
//                 ]),
//             ]);
//     }

//     public static function getRelations(): array
//     {
//         return [];
//     }

//     public static function getPages(): array
//     {
//         return [
//             'index' => Pages\ListProductCombos::route('/'),
//             'create' => Pages\CreateProductCombo::route('/create'),
//             'edit' => Pages\EditProductCombo::route('/{record}/edit'),
//         ];
//     }

//     public static function getFilteredSkus($categoryId = null): array
//     {
//         $skus = $categoryId
//             ? ProductSku::query()
//             ->join('products', 'products.id', '=', 'product_skus.product_id')
//             ->join('product_categories', 'product_categories.product_id', '=', 'products.id')
//             ->join('categories', 'categories.id', '=', 'product_categories.category_id')
//             ->where('categories.id', $categoryId)
//             ->select('product_skus.*')
//             ->with('product')
//             ->get()
//             : ProductSku::with('product')->get();

//         return $skus->map(function ($sku) {
//             return [
//                 'selected' => false,
//                 'sku_id' => $sku->id,
//                 'productName' => $sku->product->name ?? 'Không tên',
//                 'quantityCombo' => $sku->quantity,
//                 'image_url' => is_string($sku->images) ? json_decode($sku->images, true) : ($sku->images ?? []),
//                 // 'sku_quantity' => 1,
//             ];
//         })->toArray();
//     }



//     public static function afterUpdate(Model $record, array $data): void
//     {
//         $record->comboSkus()->delete();
//         foreach ($data['combo_items'] ?? [] as $item) {
//             if (!empty($item['selected']) && !empty($item['sku_id'])) {
//                 ComboSku::create([
//                     'combo_id' => $record->id,
//                     'sku_id' => $item['sku_id'],
//                     // 'quantity' => $item['sku_quantity'] ?? 1,
//                 ]);
//             }
//         }
//     }
// }
