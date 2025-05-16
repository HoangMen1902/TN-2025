<?php

namespace Modules\DetailModule\Livewire\Components;

use App\Models\Product;
use Livewire\Component;

class GeneralInformation extends Component
{
    public $data;
    public $id;
    public function mount($id) {
        $this->id = $id;
        $this->data = Product::findOrFail($this->id);
    }
    public function render()
    {
        return view('detailmodule::livewire.components.general-information');
    }
}
