<?php

namespace Modules\MiniGame\Livewire\Component;

use App\Models\Prize;
use Livewire\Component;

class Minigame extends Component
{
    public array $prizes = [];

    public function mount(): void
    {

        $this->prizes = Prize::pluck('name')->toArray();
    }

    public function render()
    {
        return view('minigame::livewire.component.minigame');
    }
}
