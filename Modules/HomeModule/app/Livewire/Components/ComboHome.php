<?php

namespace Modules\HomeModule\Livewire\Components;

use App\Models\ProductCombo;
use Livewire\Component;
use Livewire\WithPagination;
class ComboHome extends Component
{   
    use WithPagination;
    public function render()
    {
         $combos = ProductCombo::with(['productSkus.product'])
            ->where('expired_at', '>', now())
            ->paginate(12);
        return view('homemodule::livewire.components.combo-home', [
            'combos' => $combos]);
    }
}
