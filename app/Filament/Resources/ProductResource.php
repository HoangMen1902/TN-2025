<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Forms\Components\YearPicker;
use App\Models\Option;
use App\Models\OptionValue;
use App\Models\Product;
use App\Models\ProductSku;
use CodeWithDennis\FilamentSelectTree\SelectTree;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use function Laravel\Prompts\select;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Quản lý Sản phẩm';

    protected static ?string $label = 'Sản phẩm';

    protected static ?int $navigationSort = 10;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->label('Tên sách')->rules('required')->validationMessages(['required' => 'Vui lòng điền thông tin *', 'unique' => 'Sản phẩm này đã tồn tại'])->unique(ignoreRecord: true),
                TextInput::make('author')->label('Tác giả')->rules('required')->validationMessages(['required' => 'Vui lòng điền thông tin *']),
                RichEditor::make('short_description')->label('Mô tả ngắn')->rules('required')->validationMessages(['required' => 'Vui lòng điền thông tin *']),
                RichEditor::make('description')->label('Mô tả')->rules('required')->validationMessages(['required' => 'Vui lòng điền thông tin *']),
                Section::make('Thông tin sản phẩm')
                ->description('Thông tin chi tiết của sản phẩm')
                ->schema([
                    YearPicker::make('product_released_year')->label('Năm xuất bản')->rules('required')->validationMessages(['required'=> 'Vui lòng điền thông tin này']),
                    TextInput::make('weight')->label('Trọng lượng')->numeric()->rules('required')->validationMessages(['required'=> 'Vui lòng điền thông tin này']),
                    TextInput::make('width')->label('Chiều rộng')->numeric()->rules('required')->validationMessages(['required'=> 'Vui lòng điền thông tin này']),
                    TextInput::make('height')->label('Chiều cao')->numeric()->rules('required')->validationMessages(['required'=> 'Vui lòng điền thông tin này']),
                    TextInput::make('pages')->label('Số trang')->numeric()->rules('required')->validationMessages(['required'=> 'Vui lòng điền thông tin này']),
                    Select::make('book_cover')->label('Loại bìa')->options(['Bìa cứng' => 'Bìa cứng', 'Bìa mềm' => 'Bìa mềm'])->rules('required')->validationMessages(['required'=> 'Vui lòng điền thông tin này'])
                ])->columns(2),
                FileUpload::make('thumbnail')->label('Ảnh sản phẩm')->rules('required')->image()->validationMessages(['required' => 'Vui lòng nhập ảnh', 'image' => 'File tải lên không phải hình ảnh'])->columnSpanFull(),
                Repeater::make('productSkus')->relationship()->schema([
                    TextInput::make('sku')->label('Mã SKU')->rules('required')->validationMessages(['required' => 'Vui lòng nhập thông tin *', 'unique' => 'Mã SKU đã tôn tại'])->columnSpanFull()->unique(ignoreRecord: true)->columnSpan(1),
                    TextInput::make('price')->numeric()->label('Giá')->rules('required')->validationMessages(['required' => 'Vui lòng nhập thông tin *'])->columnSpan(1),
                    TextInput::make('sale_price')->numeric()->label('Giá giảm (Tùy chọn)')->columnSpan(1),
                    TextInput::make('quantity')->numeric()->label('Số lượng')->rules('required')->validationMessages(['required' => 'Vui lòng nhập thông tin *']),
                    Repeater::make('skuValues')->relationship('skuValues')->label('Thuộc tính')->schema([
                        Select::make('option_id')->label('Thuộc tính')->options(Option::pluck('name', 'id'))->reactive()->afterStateUpdated(function (callable $set) {
                            $set('value_id', null);
                        })->searchable()->rule(['required'])->validationMessages(['Vui lòng chọn thuộc tính']),
                        Select::make('value_id')->label('Giá trị')->options(function (callable $get) {
                            $optionId = $get('option_id');
                            return $optionId ? OptionValue::where('option_id', $optionId)->pluck('value_name', 'id') : [];
                        })->searchable()->rules('required')->validationMessages(['required' => 'Vui lòng chọn giá trị *'])
                    ])->columns(2)->columnSpanFull(),
                    FileUpload::make('images')
                    ->label('Ảnh sản phẩm')
                    ->required()
                    ->image()
                    ->multiple()
                    ->maxFiles(10)
                    ->columnSpanFull()
                    ->validationMessages([
                        'required' => 'Vui lòng tải lên ít nhất 1 ảnh sản phẩm',
                    ]),
                ])->defaultItems(1)->addable(true)->deletable(true)->label('Biến thể')->rules(['min:1'])->validationMessages(['min' => 'Sản phẩm phải có ít nhất một thuộc tính.'])->columns(2)->columnSpanFull(),
                SelectTree::make('categories')
                ->label('Phân loại sản phẩm')
                ->relationship('categories', 'name', 'parent_id')
                ->placeholder('Vui lòng chọn phân loại sản phẩm')
                ->searchable(),
                Select::make('publisher_id')->label('Nhà xuất bản')
                ->preload()
                ->relationship('publisher', 'publisher_name') 
                ->rules('required')->validationMessages(['required' => 'Vui lòng chọn nhà xuất bản']),
                Select::make('product_status')
                    ->label('Trạng thái')
                    ->default('active')
                    ->options([
                        'active' => 'Hoạt động',
                        'intactive' => 'Khóa'
                    ])
                    ->formatStateUsing(fn($state) => $state ? 'active' : 'inactive')
                    ->dehydrateStateUsing(fn($state) => $state ? 'active' : 'inactive')
                    ->rules('required')
                    ->validationMessages(['required' => 'Vui lòng chọn trạng thái *']),
                DateTimePicker::make('published_at')->label('Thời gian mở bán')
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('ID'),
                TextColumn::make('name')->label('Tên'),
                TextColumn::make('variant')->label('Biến thể')->getStateUsing(fn($record) => ProductSku::where('product_id', $record->id)->count('sku')),
                TextColumn::make('product_status')->label('Trạng thái')->badge()->formatStateUsing(function ($state) {
                    return match ($state) {
                        'active' => 'Hoạt động',
                        'inactive' => 'Khóa',
                        default => 'Không xác định'
                    };
                })->color(fn($state) => $state === 'active' ? 'success' : 'danger')->searchable(),
            ])->defaultSort('created_at', 'desc')
            ->filters([
                //
                TrashedFilter::make(),
                SelectFilter::make('role')
                    ->label('Lọc theo vai trò')
                    ->options([
                        'admin' => 'Quản trị',
                        'user' => 'Khách hàng',
                    ]),

                SelectFilter::make('user_status')
                    ->label('Lọc theo trạng thái')
                    ->options([
                        'active' => 'Hoạt động',
                        'inactive' => 'Khóa',
                    ]),

                TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])->defaultSort('created_at', 'desc')
            ->modifyQueryUsing(fn(Builder $query) => $query->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]));
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
