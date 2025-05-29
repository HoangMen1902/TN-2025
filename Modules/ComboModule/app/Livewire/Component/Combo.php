<?php

namespace Modules\ComboModule\Livewire\Component;

use App\Models\ProductCombo;
use Livewire\Component;
use Livewire\WithPagination;

class Combo extends Component
{
    use WithPagination;
    
    public function render()
    {
        $combos = ProductCombo::with(['productSkus.product'])
            ->whereNull('deleted_at')
            ->paginate(12);

        return view('combomodule::livewire.component.combo', [
            'combos' => $combos
        ]);
    }
}
