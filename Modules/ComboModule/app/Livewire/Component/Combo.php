<?php

namespace Modules\ComboModule\Livewire\Component;

use App\Models\ProductCombo;
use Livewire\Component;

class Combo extends Component
{
    public $combos;
    public $displayLimit = 5;  // Hiển thị ban đầu 5 combo
    public $maxLimit;

    public function mount()
    {
        $this->maxLimit = ProductCombo::whereNull('deleted_at')->count();
        $this->loadCombos();
    }

    public function loadCombos()
    {
        $this->combos = ProductCombo::with(['productSkus.product'])
            ->whereNull('deleted_at')
            ->limit($this->displayLimit)
            ->get();
    }

    // Tham số $count cho biết số combo muốn load thêm lần này
    public function loadMore($count = 5)
    {
        if ($this->displayLimit < $this->maxLimit) {
            $this->displayLimit += $count;

            if ($this->displayLimit > $this->maxLimit) {
                $this->displayLimit = $this->maxLimit;
            }

            $this->loadCombos();
        }
    }

    public function render()
    {
        return view('combomodule::livewire.component.combo', [
            'combos' => $this->combos,
            'canLoadMore' => $this->displayLimit < $this->maxLimit,
        ]);
    }
}
