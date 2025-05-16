<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Category as CategoryModel;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Group;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\ToggleColumn;

class Category extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    protected int $maxDepth = 100;

    public function table(Table $table): Table
    {
        return $table
            ->query(CategoryModel::with('parent'))
            ->defaultPaginationPageOption(50)
            ->columns([
                TextColumn::make('parent.name')
                    ->label('Danh mục cha')
                    ->sortable()
                    ->formatStateUsing(fn($state, $record) => $record->parent?->name ?? '—'),
                TextColumn::make('name')->label('Tên danh mục'),
                ToggleColumn::make('category_status')
                    ->label('Trạng thái')
                    ->onColor('success')
                    ->offColor('danger')
                    ->onIcon('heroicon-s-check-circle')
                    ->offIcon('heroicon-s-x-circle')
                    ->updateStateUsing(function ($record, $state) {
                        $record->update(['category_status' => $state ? 'active' : 'inactive']);
                    })
                    ->tooltip(fn($record) => $record->category_status === 'active' ? 'Nhấn để hủy kích hoạt' : 'Nhấn để kích hoạt') // Optional: tooltip
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
            ->actions([
                EditAction::make()
                    ->label('Sửa')
                    ->form([
                        TextInput::make('name')->label('Tên danh mục')->required(),
                        Select::make('parent_id')
                            ->label('Danh mục cha')
                            ->options(CategoryModel::pluck('name', 'id'))
                            ->searchable()
                            ->preload()
                            ->nullable()
                            ->default(null),
                    ])->modalHeading('Chỉnh sửa danh mục')
                    ->modalSubmitActionLabel('Xác nhận')
                    ->modalCancelActionLabel('Hủy')

                    ->using(function ($record, array $data) {
                        $record->update([
                            'name' => $data['name'],
                        ]);
                    }),

                DeleteAction::make()
                    ->label('Xóa')
                    ->requiresConfirmation()
                    ->modalHeading('Xóa danh mục')
                    ->modalSubheading('Bạn có chắc chắn muốn xóa danh mục này?')
                    ->modalSubmitActionLabel('Xác nhận')
                    ->modalCancelActionLabel('Hủy'),
            ]);
    }


    public function recursiveCategoryRepeater(int $level = 0): Group
    {
        $schema = [
            TextInput::make('name')
                ->label(str_repeat('—', $level) . ' Tên danh mục')
                ->required(),
        ];

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

        $category = CategoryModel::create([
            'name' => $data['name'],
            'parent_id' => $parentId,
        ]);

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
