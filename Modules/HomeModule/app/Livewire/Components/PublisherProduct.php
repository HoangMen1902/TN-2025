<?php

namespace Modules\HomeModule\Livewire\Components;

use App\Models\Publisher;
use Livewire\Component;

class PublisherProduct extends Component
{
    public $data;

    public function mount() {
        $this->data = $this->data = Publisher::where('publisher_status', 'active')
        ->has('products')
        ->with('products')
        ->limit(3)
        ->orderBy('created_at', 'desc')
        ->get();;
    }
    public function render()
    {
        return view('homemodule::livewire..components.publisher-product');
    }
}
