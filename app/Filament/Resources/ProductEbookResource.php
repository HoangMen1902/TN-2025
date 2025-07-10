<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductEbookResource\Pages;
use App\Filament\Resources\ProductEbookResource\RelationManagers\ChaptersRelationManager;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductEbook;
use App\Models\ProductTag;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use CodeWithDennis\FilamentSelectTree\SelectTree;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Form;
use Modules\EbookModule\Http\Controllers\EpubSplitController;
use Modules\EbookModule\Http\Controllers\PdfSplitController;

class ProductEbookResource extends Resource
{
    protected static ?string $model = ProductEbook::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Quản lý Sản phẩm';
    protected static ?string $navigationLabel = 'Ebook';
    protected static ?string $pluralModelLabel = 'Ebook';
    protected static ?string $modelLabel = 'Ebook';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('product_id')
                ->label('Liên kết sản phẩm có sẵn')
                ->relationship('product', 'name')
                ->searchable()
                ->preload()
                ->reactive()
                ->placeholder('--- Tạo mới ebook ---')
                ->afterStateUpdated(function ($state, callable $set) {
                    if (!$state) {
                        $set('title', null);
                        $set('author', null);
                        $set('categories', null);
                        $set('tags', null);
                    }
                }),

            Section::make('Thông tin Ebook')
                ->schema([
                    TextInput::make('title')
                        ->label('Tiêu đề')
                        ->required()
                        ->maxLength(255),

                    TextInput::make('author')
                        ->label('Tác giả')
                        ->required()
                        ->maxLength(255),

                    SelectTree::make('categories')
                        ->label('Phân loại sản phẩm')
                        ->relationship('categories', 'name', 'parent_id')
                        ->placeholder('Vui lòng chọn phân loại sản phẩm')
                        ->required()
                        ->multiple()
                        ->searchable(),

                    Select::make('tags')
                        ->label('Thẻ (Tùy chọn)')
                        ->relationship('tags', 'tag_name')
                        ->preload()
                        ->searchable()
                        ->multiple()
                        ->placeholder('Chọn các thẻ liên quan'),
                ]),

            Textarea::make('description')
                ->label('Mô tả')
                ->rows(3),

            FileUpload::make('file_path')
                ->label('Tệp Ebook') // Đổi nhãn để phản ánh cả PDF và ePub
                ->disk('public')
                ->directory('ebooks')
                ->acceptedFileTypes(['application/pdf', 'application/epub+zip'])
                ->required(),

            FileUpload::make('cover_image')
                ->label('Ảnh bìa')
                ->disk('public')
                ->directory('ebooks/covers')
                ->acceptedFileTypes(['image/png', 'image/jpeg', 'image/jpg', 'image/webp', 'image/avif'])
                ->image()
                ->required(),

            TextInput::make('price')
                ->label('Giá')
                ->numeric()
                ->required()
                ->minValue(0),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('id'),
            TextColumn::make('product.name')
                ->label('Sản phẩm')
                ->sortable()
                ->limit(30)
                ->searchable(),
            TextColumn::make('title')
                ->label('Tiêu đề')
                ->limit(30),
            TextColumn::make('price')
                ->label('Giá')
                ->money('VND')
                ->sortable(),
            TextColumn::make('categories')
                ->label('Danh mục')
                ->formatStateUsing(fn($record) => $record->categories->pluck('name')->join(', '))
                ->limit(30),
            TextColumn::make('tags')
                ->label('Thẻ')
                ->formatStateUsing(fn($record) => $record->tags->pluck('tag_name')->join(', '))
                ->limit(30),
            TextColumn::make('created_at')
                ->label('Tạo lúc')
                ->dateTime('d/m/Y H:i'),
        ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
                Action::make('split_chapters')
                    ->label('Tách Chương')
                    ->icon('heroicon-o-scissors')
                    ->form(function (Model $record) {
                        $filePath = $record->file_path;
                        $extension = pathinfo($filePath, PATHINFO_EXTENSION);

                        if ($extension === 'pdf') {
                            return [
                                Forms\Components\Repeater::make('chapters')
                                    ->label('Danh sách chương')
                                    ->schema([
                                        TextInput::make('chapter_name')
                                            ->label('Tên chương')
                                            ->required(),
                                        TextInput::make('start_page')
                                            ->label('Trang bắt đầu')
                                            ->numeric()
                                            ->required()
                                            ->minValue(1),
                                        TextInput::make('end_page')
                                            ->label('Trang kết thúc')
                                            ->numeric()
                                            ->required()
                                            ->minValue(1),
                                    ])
                                    ->columns(3),
                            ];
                        }
                        return []; // Không cần form cho ePub
                    })
                    ->action(function (Model $record, array $data) {
                        $filePath = $record->file_path;
                        $extension = pathinfo($filePath, PATHINFO_EXTENSION);

                        if ($extension === 'pdf') {
                            $controller = new PdfSplitController();
                            $controller->splitPdf($record->id, $data['chapters'] ?? []);
                        } elseif ($extension === 'epub') {
                            $controller = new EpubSplitController();
                            $controller->splitEpub($record->id);
                        } else {
                            throw new \Exception('Định dạng file không được hỗ trợ.');
                        }
                    }),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }

    public static function beforeCreate(array $data): array
    {
        return DB::transaction(function () use ($data) {
            if (empty($data['title']) || empty($data['author']) || empty($data['price']) || empty($data['file_path']) || empty($data['cover_image'])) {
                throw new \Exception('Thiếu thông tin bắt buộc để tạo ebook.');
            }

            if (!empty($data['product_id']) && !Product::find($data['product_id'])) {
                throw new \Exception('Sản phẩm liên kết không tồn tại.');
            }

            return $data;
        });
    }

    public static function afterCreate(Model $record, array $data): void
    {
        if (!empty($data['tags'])) {
            $record->tags()->sync($data['tags']);
        }
        if (!empty($data['categories'])) {
            $record->categories()->sync($data['categories']);
        }
    }

    public static function afterUpdate(Model $record, array $data): void
    {
        if (array_key_exists('tags', $data)) {
            $record->tags()->sync($data['tags'] ?? []);
        }
        if (array_key_exists('categories', $data)) {
            $record->categories()->sync($data['categories'] ?? []);
        }
    }

    public static function getRelations(): array
    {
        return [
            ChaptersRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProductEbooks::route('/'),
            'create' => Pages\CreateProductEbook::route('/create'),
            'edit' => Pages\EditProductEbook::route('/{record}/edit'),
        ];
    }
}