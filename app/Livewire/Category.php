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

class Category extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    protected int $maxDepth = 100;

    public function table(Table $table): Table
    {
        return $table
            ->query(CategoryModel::with('parent'))
            ->columns([
                TextColumn::make('parent.name')
                    ->label('Danh mục cha')
                    ->sortable()
                    ->formatStateUsing(fn($state, $record) => $record->parent?->name ?? '—'),
                TextColumn::make('name')->label('Tên danh mục'),
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
