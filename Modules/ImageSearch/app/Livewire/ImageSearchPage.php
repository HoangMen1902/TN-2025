<?php

namespace Modules\ImageSearch\Livewire;

use Livewire\Component;

class ImageSearchPage extends Component
{
    public $products;
    public $data;
    public function mount() {
        $this->products = $this->data;
    }

    public function render()
    {
        return view('imagesearch::livewire.image-search-page');
    }
}
