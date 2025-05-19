<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductComboResource\Pages;
use App\Models\ProductCombo;
use App\Models\comboSku;
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
use Filament\Forms\Components\Hidden;
use Illuminate\Database\Eloquent\Model;
// ... các namespace như cũ

class ProductComboResource extends Resource
{
    protected static ?string $model = ProductCombo::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Quản lý Sản phẩm';
    protected static ?string $navigationLabel = 'Combo';
    protected static ?string $pluralModelLabel = 'Combo';
    protected static ?string $modelLabel = 'Combo';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Grid::make(4)->schema([
                Textarea::make('description')
                    ->label('Mô tả combo')
                    ->rows(3)
                    ->columnSpan(4),

                TextInput::make('original_price')
                    ->label('Giá gốc')
                    ->numeric()
                    ->columnSpan(1),

                TextInput::make('sale_price')
                    ->label('Giá giảm')
                    ->numeric()
                    ->columnSpan(1),

                DatePicker::make('expired_at')
                    ->label('Ngày hết hạn')
                    ->columnSpan(1),

                Select::make('category_filter')
                    ->label('Lọc theo loại sản phẩm')
                    ->options(fn () => Category::pluck('name', 'id'))
                    ->reactive()
                    ->afterStateUpdated(fn($state, callable $set) => $set('combo_items', static::getFilteredSkus($state)))
                    ->columnSpan(2),

                TextInput::make('quantity')
                    ->label('Số lượng Combo')
                    ->numeric()
                    ->required()
                    ->minValue(1)
                    ->columnSpan(1),

                Repeater::make('combo_items')
                    ->schema([
                        Checkbox::make('selected')->label('Chọn'),

                        Hidden::make('sku_id'),
                        Hidden::make('productName'),
                        // Hidden::make('quantity'),
                        Hidden::make('image_url'),

                        Placeholder::make('Tên sản phẩm')
                            ->content(fn($get) => $get('productName')),

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
                    ->columns(4)
                    ->default(fn() => static::getFilteredSkus(null))
                    ->label('Danh sách SKU')
                    ->hiddenLabel()
                    ->columnSpanFull()
                    ->afterStateHydrated(function ($state, callable $get, callable $set) {
                        // Validate tổng số lượng SKU trong combo không vượt quá quantityCombo
                        $quantityCombo = $get('quantityCombo');
                        $totalSelected = collect($state)
                            ->filter(fn ($item) => $item['selected'] ?? false)
                            ->sum(fn ($item) => $item['sku_quantity'] ?? 0);

                        if ($quantityCombo !== null && $totalSelected > $quantityCombo) {
                            // Đây là điểm kiểm tra, trong thực tế nên đưa về validation rule
                        }
                    }),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('ID'),
                TextColumn::make('description')->label('Mô tả'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            ? ProductSku::query()
                ->join('products', 'products.id', '=', 'product_skus.product_id')
                ->join('product_categories', 'product_categories.product_id', '=', 'products.id')
                ->join('categories', 'categories.id', '=', 'product_categories.category_id')
                ->where('categories.id', $categoryId)
                ->select('product_skus.*')
                ->with('product')
                ->get()
            : ProductSku::with('product')->get();

        return $skus->map(function ($sku) {
            return [
                'selected' => false,
                'sku_id' => $sku->id,
                'productName' => $sku->product->name ?? 'Không tên',
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
