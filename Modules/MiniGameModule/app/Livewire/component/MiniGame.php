<?php

namespace Modules\MiniGameModule\App\Livewire\Component;

use Livewire\Component;

class MiniGame extends Component
{   
    public $prizes = [
  "Giảm 10%",
  "-20K đơn ≥150K",
  "Free Ship",
  "-50K đơn ≥300K",
  "Mua 2 tặng bookmark",
  "Giảm 15% sách mới",
  "-100K khách mới",
  "+1 lượt quay"
];
    public function render()
    {
         return view('minigamemodule::Livewire.component.mini-game');
    }
}
