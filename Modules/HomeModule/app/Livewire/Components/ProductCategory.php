<?php

namespace Modules\HomeModule\Livewire\Components;

use Livewire\Component;
use Illuminate\Database\Eloquent\Collection;
class ProductCategory extends Component
{
    public $childCategories;

    public function mount(Collection $childCategories)
    {
        $this->childCategories = $childCategories;
    }
    public function render()
    {
        return view('homemodule::livewire.components.product-category');
    }
}
