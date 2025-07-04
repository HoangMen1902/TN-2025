<?php

namespace Modules\MiniGame\Livewire\Component;

use App\Models\Prize;
use Livewire\Attributes\On;
use Livewire\Component;
use App\Models\WonPrize;
use Illuminate\Support\Facades\Auth;

class Minigame extends Component
{
    public array $prizes = [];


    public array $history = [];

    public function mount(): void
    {
        $this->prizes = Prize::pluck('name')->toArray();

        $this->loadHistory();
    }
    public function loadHistory(): void
{
    $this->history = WonPrize::with('prize')
        ->where('user_id', Auth::id())
        ->latest()
        ->take(10)
        ->get()
        ->map(function ($item) {
            return [
                'name' => $item->prize->name ?? 'Không xác định',
                'won_at' => $item->won_at->format('d/m/Y H:i'),
            ];
        })
        ->toArray();
}

    #[On('claimPrize')]
    public function claimPrize(string $prizeName): void
    {
        $prize = Prize::where('name', $prizeName)->first();

        if (!$prize) {
            $this->dispatch('notify', message: 'Phần thưởng không hợp lệ!');
            return;
        }

        if ($prize->quantity !== null && $prize->quantity <= 0) {
            $this->dispatch('notify', message: 'Phần thưởng đã hết!');
            return;
        }

        // Trừ số lượng trước
        $prize->decrement('quantity');


        WonPrize::create([
            'prize_id' => $prize->id,
            'user_id' => Auth::check() ? Auth::id() : null,
            'username' => Auth::check() ? Auth::user()->name : 'Khách',

            'won_at' => now(),
        ]);

        $this->dispatch('notify', message: "Bạn nhận được: {$prize->name}");
    }


    public function render()
    {
        return view('minigame::livewire.component.minigame');
    }
}
