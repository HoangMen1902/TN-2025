<?php

namespace Modules\MiniGameModule\App\Livewire\Component;

use Livewire\Component;
use App\Models\Prize;

class MiniGame extends Component
{
    public array $prizes = [];

    public function mount(): void
    {
        
        $this->prizes = Prize::pluck('name')->toArray();
    }

    public function render()
    {
        return view('minigamemodule::livewire.component.mini-game', [
            'prizes' => $this->prizes,
        ]);
    }
}
