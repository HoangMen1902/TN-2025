<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FlashSaleResource\Pages;
use App\Models\ProductSku;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Form as ResourceForm;
use Filament\Resources\Resource;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Table;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Actions\ForceDeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Tables\Actions\ForceDeleteBulkAction;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Get;



use App\Models\ProductCombo;
use App\Models\comboSku;

use Filament\Forms\Components\Repeater;

use Filament\Forms\Components\Grid;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Placeholder;
use Illuminate\Support\HtmlString;
use App\Models\Category;
use App\Models\Flashsale;
use Filament\Forms\Components\Hidden;
use Illuminate\Database\Eloquent\Model;


class FlashSaleResource extends Resource
{
    protected static ?string $model = Flashsale::class;

    protected static ?string $navigationIcon = 'heroicon-o-bolt';
    protected static ?string $navigationLabel = 'Flash Sale';

    protected static ?string $navigationGroup = 'Chương trình giảm giá';

    protected static ?string $modelLabel = 'Flash Sale';
    protected static ?string $pluralModelLabel = 'Flash Sale';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Thông tin chương trình')
                    ->schema([
                        TextInput::make('name')
                            ->label('Tên Flash Sale')
                            ->required()
                            ->maxLength(255),

                        DateTimePicker::make('started_at')
                            ->label('Thời gian bắt đầu')
                            ->required()
                            ->native(false),

                        DateTimePicker::make('expired_at')
                            ->label('Thời gian kết thúc')
                            ->required()
                            ->native(false),
                    ])->columns(2),

                Section::make('Giảm giá áp dụng')
                    ->relationship('discount')
                    ->schema([
                        Select::make('discount_type')
                            ->label('Loại giảm')
                            ->required()
                            ->options([
                                'percent' => 'Phần trăm (%)',
                                'specific' => 'Giá trị cố định',
                            ]),

                        TextInput::make('discount_amount')
                            ->label('Giá trị')
                            ->numeric()
                            ->required(),
                    ])->columns(2),

                Section::make('Hình thức áp dụng')
                    ->schema([
                        Radio::make('apply_type')
                            ->label('Chọn cách áp dụng Flash Sale')
                            ->options([
                                'category' => 'Theo danh mục',
                                'sku' => 'Theo SKU sản phẩm',
                                'both' => 'Cả hai',
                            ])
                            ->default('sku')
                            ->inline()
                            ->live()
                            ->required(),
                    ]),

                Section::make('Sản phẩm áp dụng')
                    ->schema([
                        CheckboxList::make('categories')
                            ->label('Chọn loại sản phẩm cụ thể')
                            ->relationship('categories', 'name')
                            ->searchable()
                            ->columns(3)
                            ->columnSpan('full')
                            ->visible(fn(Get $get): bool => in_array($get('apply_type'), ['category', 'both']))
                            ->required(fn(Get $get): bool => in_array($get('apply_type'), ['category', 'both'])),


                        Select::make('sort_by')
                            ->label('Sắp xếp theo trường')
                            ->options([
                                'price' => 'Giá',
                                'quantity' => 'Số lượng',
                            ])
                            ->default('price')
                            ->reactive()
                            ->afterStateUpdated(
                                fn($state, callable $set, callable $get) =>
                                $set('skus', static::getFilteredSkus(
                                    $get('category_id'),
                                    $state,                      // sort_by
                                    $get('sort_order') ?? 'asc' // direction
                                ))
                            )
                            ->visible(fn(Get $get): bool => in_array($get('apply_type'), ['sku', 'both']))
                            ->required(fn(Get $get): bool => in_array($get('apply_type'), ['sku', 'both']))
                            ->columns(1),

                        Select::make('sort_order')
                            ->label('Thứ tự sắp xếp')
                            ->options([
                                'asc' => 'Tăng dần',
                                'desc' => 'Giảm dần',
                            ])
                            ->default('asc')
                            ->reactive()
                            ->afterStateUpdated(
                                fn($state, callable $set, callable $get) =>
                                $set('skus', static::getFilteredSkus(
                                    $get('category_id'),
                                    $get('sort_by') ?? 'price', // sort_by
                                    $state                      // direction
                                ))
                            )
                            ->visible(fn(Get $get): bool => in_array($get('apply_type'), ['sku', 'both']))
                            ->required(fn(Get $get): bool => in_array($get('apply_type'), ['sku', 'both'])),

                        Select::make('product_id')
                            ->label('Tìm kiếm sản phẩm')
                            ->relationship('skus.product', 'name')
                            ->searchable()
                            ->reactive()
                            ->afterStateUpdated(
                                fn($state, callable $set) =>
                                $set('skus', static::getFilteredSkus($state))
                            )
                            ->visible(fn(Get $get): bool => in_array($get('apply_type'), ['sku', 'both']))
,

                        Repeater::make('skus')
                            ->schema([
                                Checkbox::make('selected')->label('Chọn'),
                                Hidden::make('sku_id'),
                                Hidden::make('productName'),
                                Hidden::make('image_url'),

                                Placeholder::make('Tên sản phẩm')
                                    ->content(fn($get) => $get('productName')),

                                Placeholder::make('Số lượng còn')
                                    ->content(fn($get) => $get('quantity')),
                                Placeholder::make('Giá sản phẩm')
                                    ->content(fn($get) => $get('price')),
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
                            ->default(fn(Get $get) => static::getFilteredSkus($get('product_id')))
                            ->label('Danh sách SKU')
                            ->columnSpanFull()
                            ->visible(fn(Get $get): bool => in_array($get('apply_type'), ['sku', 'both']))
                            ->required(fn(Get $get): bool => in_array($get('apply_type'), ['sku', 'both']))


                    ])
                    ->columns(3)
                    ->visible(fn(Get $get): bool => in_array($get('apply_type'), ['category', 'sku', 'both'])),


            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Tên chương trình')->searchable(),
                TextColumn::make('started_at')->label('Bắt đầu')->dateTime(),
                TextColumn::make('expired_at')->label('Kết thúc')->dateTime(),
                TextColumn::make('discount.discount_type')
                    ->label('Loại giảm')
                    ->formatStateUsing(function ($state) {
                        return match ($state) {
                            'percent' => 'Phần trăm (%)',
                            'specific' => 'Giá trị cố định',
                            default => 'Không xác định',
                        };
                    }),
                TextColumn::make('discount.discount_amount')->label('Giá trị'),
                TextColumn::make('skus_count')
                    ->label('Số SKU')
                    ->counts('skus')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
                RestoreAction::make(),
                ForceDeleteAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
                RestoreBulkAction::make(),
                ForceDeleteBulkAction::make(),
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
            'index' => Pages\ListFlashSales::route('/'),
            'create' => Pages\CreateFlashSale::route('/create'),
            'edit' => Pages\EditFlashSale::route('/{record}/edit'),
        ];
    }

    public static function getFilteredSkus($categoryId = null, $sortBy = 'price', $direction = 'asc'): array
    {
        // Chỉ cho phép sắp xếp theo các cột hợp lệ
        $allowedSortFields = ['price', 'quantity'];
        $allowedDirections = ['asc', 'desc'];

        if (!in_array($sortBy, $allowedSortFields)) {
            $sortBy = 'price';
        }

        if (!in_array(strtolower($direction), $allowedDirections)) {
            $direction = 'asc';
        }

        $query = ProductSku::query()
            ->join('products', 'products.id', '=', 'product_skus.product_id')
            ->leftJoin('product_categories', 'product_categories.product_id', '=', 'products.id')
            ->leftJoin('categories', 'categories.id', '=', 'product_categories.category_id')
            ->select('product_skus.*')
            ->with('product');

        if ($categoryId) {
            $query->where('categories.id', $categoryId);
        }

        $skus = $query->orderBy("product_skus.$sortBy", $direction)->get();

        return $skus->map(function ($sku) {
            return [
                'selected' => false,
                'sku_id' => $sku->id,
                'productName' => ($sku->product->name ?? 'Không rõ') . ' - ' . ($sku->sku ?? 'Không tên'),
                'quantity' => $sku->quantity,
                'sku_quantity' => 1,
                'image_url' => is_string($sku->images) ? json_decode($sku->images, true) : ($sku->images ?? []),
                'price' => $sku->price,
            ];
        })->toArray();
    }



    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
