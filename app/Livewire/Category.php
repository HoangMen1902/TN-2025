<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Category as CategoryModel;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Filters\TrashedFilter;
use Illuminate\Support\Str;

class Category extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;
    protected int $maxDepth = 100;

    public function table(Table $table): Table
    {
        return $table
            ->query(CategoryModel::with('parent')->withTrashed())
            ->defaultPaginationPageOption(50)
            ->columns([
                ImageColumn::make('image')
                    ->label('Hình ảnh')
                    ->getStateUsing(fn($record) => $record->image ? asset('storage/' . $record->image) : null)
                    ->square()
                    ->size(80),
                TextColumn::make('parent.name')
                    ->label('Danh mục cha')
                    ->sortable()
                    ->formatStateUsing(fn($state, $record) => $record->parent?->name ?? '—'),
                TextColumn::make('name')->label('Tên danh mục'),
                TextColumn::make('slug')->label('Slug')->sortable(),
                ToggleColumn::make('category_status_bool')
                    ->label('Trạng thái')
                    ->afterStateUpdated(function ($record, $state) {
                        $record->category_status_bool = $state;
                        $record->save();
                    })
                    ->tooltip(fn($record) => $record->category_status === 'active' ? 'Nhấn để hủy kích hoạt' : 'Nhấn để kích hoạt')
                    ->sortable(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Tạo danh mục')
                    ->form([
                        $this->recursiveCategoryRepeater(),
                    ])
                    ->createAnother(false)
                    ->using(function (array $data) {
                        return $this->createCategoryWithChildren($data);
                    }),
            ])
            ->filters([
                Filter::make('name')
                    ->form([
                        TextInput::make('name')->label('Tên danh mục'),
                    ])
                    ->query(function ($query, $data) {
                        return $query->when(
                            $data['name'],
                            fn($q) => $q->where('name', 'like', '%' . $data['name'] . '%')
                        );
                    }),

                SelectFilter::make('category_status')
                    ->label('Trạng thái')
                    ->options([
                        'active' => 'Kích hoạt',
                        'inactive' => 'Không kích hoạt',
                    ]),
                SelectFilter::make('trashed')
                    ->label('Trạng thái xóa')
                    ->options([
                        'all' => 'Tất cả',
                        'only' => 'Chỉ mục đã xóa',
                        'without' => 'Chỉ mục chưa xóa',
                    ])
                    ->default('without')
                    ->query(function ($query, array $data) {
                        return match ($data['value']) {
                            'only' => $query->onlyTrashed(),
                            'without' => $query->withoutTrashed(),
                            'all' => $query->withTrashed(),
                            default => $query,
                        };
                    }),
            ])
            ->actions([
                EditAction::make()
                    ->label('Sửa')
                    ->form(function ($record) {
                        $fields = [
                            FileUpload::make('image')
                                ->label('Hình ảnh')
                                ->image()
                                ->directory('categories')
                                ->maxSize(2048),
                            TextInput::make('name')
                                ->label('Tên danh mục')
                                ->required()
                                ->rules([
                                    'required',
                                    'max:255',
                                ])->unique(ignoreRecord: true)
                                ->validationMessages([
                                    'required' => 'Vui lòng nhập tên danh mục.',
                                    'unique' => 'Tên danh mục đã tồn tại.',
                                    'max' => 'Tên danh mục không được vượt quá :max ký tự.',
                                ]),
                            Select::make('parent_id')
                                ->label('Danh mục cha')
                                ->options(CategoryModel::pluck('name', 'id'))
                                ->searchable()
                                ->preload()
                                ->nullable()
                                ->default(null),
                        ];

                        if ($record->parent_id) {
                            $fields[] = TextInput::make('slug')
                                ->label('Slug')
                                ->rules([
                                    'required',
                                    'max:255',
                                    'unique:categories,slug,' . $record->id,
                                    'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                                ])
                                ->validationMessages([
                                    'required' => 'Vui lòng nhập slug.',
                                    'unique' => 'Slug đã tồn tại.',
                                    'max' => 'Slug không được vượt quá :max ký tự.',
                                    'regex' => 'Slug chỉ được chứa chữ cái thường, số và dấu gạch ngang.',
                                ])
                                ->helperText('Để trống để tự động tạo từ tên danh mục');
                        }

                        return $fields;
                    })
                    ->modalHeading('Chỉnh sửa danh mục')
                    ->modalSubmitActionLabel('Xác nhận')
                    ->modalCancelActionLabel('Hủy')
                    ->using(function ($record, array $data) {
                        $updateData = [
                            'name' => $data['name'],
                            'parent_id' => $data['parent_id'] ?? null,
                            'image' => $data['image'] ?? $record->image,
                        ];

                        if ($record->parent_id || $data['parent_id']) {
                            if (empty($data['slug'])) {
                                $updateData['slug'] = $record->generateUniqueSlug($data['name']);
                            } else {
                                $updateData['slug'] = $data['slug'];
                            }
                        } else {
                            $updateData['slug'] = null;
                        }

                        $record->update($updateData);
                    }),

                DeleteAction::make()
                    ->label('Xóa')
                    ->requiresConfirmation()
                    ->modalHeading('Xóa danh mục')
                    ->modalSubheading('Bạn có chắc chắn muốn xóa danh mục này?')
                    ->modalSubmitActionLabel('Xác nhận')
                    ->modalCancelActionLabel('Hủy'),

                Action::make('addChild')
                    ->label('Thêm mục con')
                    ->icon('heroicon-o-plus')
                    ->form([
                        FileUpload::make('image')
                            ->label('Hình ảnh')
                            ->image()
                            ->directory('categories')
                            ->maxSize(2048),
                        TextInput::make('name')
                            ->label('Tên danh mục')
                            ->placeholder('Nhập tên danh mục con')
                            ->rules(['required', 'unique:categories,name'])
                            ->validationMessages([
                                'required' => 'Vui lòng điền tên danh mục.',
                                'unique' => 'Tên danh mục đã tồn tại.',
                            ])
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                $set('slug', Str::slug($state));
                            }),

                        TextInput::make('slug')
                            ->label('Slug')
                            ->rules([
                                'unique:categories,slug',
                                'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                            ])
                            ->validationMessages([
                                'unique' => 'Slug đã tồn tại.',
                                'regex' => 'Slug chỉ được chứa chữ cái thường, số và dấu gạch ngang.',
                            ])
                            ->helperText('Để trống để tự động tạo từ tên danh mục'),
                        Toggle::make('status')
                            ->label('Kích hoạt')
                            ->default(true),
                    ])
                    ->action(function (CategoryModel $record, array $data) {
                        $slug = $data['slug'] ?: Str::slug($data['name']);
                        $record->children()->create([
                            'name' => $data['name'],
                            'slug' => $slug,
                            'image' => $data['image'] ?? null,
                            'category_status' => $data['status'] ? 'active' : 'inactive',
                        ]);
                    })
                    ->modalHeading('Thêm danh mục con')
                    ->modalSubmitActionLabel('Thêm')
                    ->modalCancelActionLabel('Hủy')
                    ->visible(fn($record) => !$record->parent_id)
            ]);
    }

    public function recursiveCategoryRepeater(int $level = 0): Group
    {
        $schema = [
            FileUpload::make('image')
                ->label('Hình ảnh')
                ->image()
                ->directory('categories')
                ->maxSize(2048),
            TextInput::make('name')
                ->label(str_repeat('—', $level) . ' Tên danh mục')
                ->rules(['required', 'unique:categories,name'])
                ->validationMessages([
                    'required' => 'Vui lòng điền tên danh mục.',
                    'unique' => 'Tên danh mục đã tồn tại.',
                ])
                ->reactive()
                ->afterStateUpdated(function ($state, callable $set) {
                    $set('slug', Str::slug($state));
                }),
        ];

        if ($level > 0) {
            $schema[] = TextInput::make('slug')
                ->label(str_repeat('—', $level) . ' Slug')
                ->rules([
                    'unique:categories,slug',
                    'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                ])
                ->validationMessages([
                    'unique' => 'Slug đã tồn tại.',
                    'regex' => 'Slug chỉ được chứa chữ cái thường, số và dấu gạch ngang.',
                ])
                ->helperText('Để trống để tự động tạo từ tên danh mục');
        }

        if ($level < $this->maxDepth) {
            $schema[] = Repeater::make('children')
                ->label('Danh mục con')
                ->schema([
                    $this->recursiveCategoryRepeater($level + 1),
                ])
                ->defaultItems(0)
                ->collapsible()
                ->addActionLabel('Thêm danh mục con');
        }

        return Group::make($schema);
    }

    public function createCategoryWithChildren(array $data, $parentId = null): ?CategoryModel
    {
        if (empty($data['name'])) {
            return null;
        }

        $categoryData = [
            'name' => $data['name'],
            'parent_id' => $parentId,
            'image' => $data['image'] ?? null,
        ];

        if ($parentId) {
            $categoryData['slug'] = $data['slug'] ?? Str::slug($data['name']);
        }

        $category = CategoryModel::create($categoryData);

        if (!empty($data['children']) && is_array($data['children'])) {
            foreach ($data['children'] as $child) {
                $this->createCategoryWithChildren($child, $category->id);
            }
        }

        return $category;
    }

    protected function getCategoryDepth($category, $depth = 0)
    {
        if (!$category->parent) return $depth;
        return $this->getCategoryDepth($category->parent, $depth + 1);
    }

    public function render()
    {
        return view('livewire.category');
    }
}
