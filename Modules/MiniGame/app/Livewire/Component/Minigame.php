<?php

namespace Modules\MiniGame\Livewire\Component;

use App\Models\Prize;
use App\Models\WonPrize;
use App\Models\VoucherUsed;
use Livewire\Attributes\On;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class Minigame extends Component
{
    public array $prizes = [];
    public int $remainingSpins = 0;
    public string|null $nextSpinTime = null;
    public array $history = [];

    public function mount(): void
    {
        $this->prizes = Prize::pluck('name')->toArray();
        $this->loadHistory();
        $this->updateSpinInfo();
    }

    protected function isAdmin(): bool
    {
        $user = Auth::user();
        return $user && $user->email === 'admin@admin.com';
    }

    public function updateSpinInfo(): void
    {
        $user = Auth::user();
        if (!$user) return;

        if ($this->isAdmin()) {
            $this->remainingSpins = 99;
            $this->nextSpinTime = null;
            return;
        }

        $today = now()->startOfDay();

        $spinsToday = WonPrize::where('user_id', $user->id)
            ->whereDate('won_at', $today)
            ->orderByDesc('won_at')
            ->get();

        $count = $spinsToday->count();
        $this->remainingSpins = max(0, 2 - $count);

        if ($count === 1) {
            $lastSpin = $spinsToday->first()->won_at;
            $nextSpin = $lastSpin->copy()->addHours(12);
            $this->nextSpinTime = $nextSpin->isFuture() ? $nextSpin->toIso8601String() : null;
        } elseif ($count >= 2) {
            $this->nextSpinTime = null;
        }
    }

    public function loadHistory(): void
    {
        $userId = Auth::id();
        if (!$userId) return;

        $this->history = WonPrize::with('prize')
            ->where('user_id', $userId)
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

    public function canSpin(): bool
    {
        $user = Auth::user();
        if (!$user) return false;

        if ($this->isAdmin()) return true;

        $today = Carbon::today();

        $spinsToday = WonPrize::where('user_id', $user->id)
            ->whereDate('won_at', $today)
            ->orderByDesc('won_at')
            ->get();

        $count = $spinsToday->count();

        if ($count >= 2) return false;

        if ($count === 1) {
            $lastSpin = $spinsToday->first()->won_at;
            $nextSpin = $lastSpin->copy()->addHours(12);
            return now()->greaterThanOrEqualTo($nextSpin);
        }

        return true;
    }

    protected function getRandomPrize(): ?Prize
    {
        $prizes = Prize::where(function ($query) {
            $query->whereNull('quantity')->orWhere('quantity', '>', 0);
        })->get();

        $total = $prizes->sum('probability');
        if ($total <= 0) return null;

        $rand = mt_rand() / mt_getrandmax();
        $cumulative = 0;

        foreach ($prizes as $prize) {
            $cumulative += $prize->probability / $total;
            if ($rand <= $cumulative) return $prize;
        }

        return $prizes->last(); // fallback
    }

    #[On('claimPrize')]
public function claimPrize(): void
{
    $user = Auth::user();

    if (!$user) {
        $this->dispatch('notify', message: 'Bạn cần đăng nhập để quay thưởng!');
        return;
    }

    if (!$this->canSpin()) {
        $this->dispatch('notify', message: 'Bạn đã hết lượt quay miễn phí trong ngày!');
        return;
    }

    $prize = $this->getRandomPrize();

    if (!$prize) {
        $this->dispatch('notify', message: 'Không có phần thưởng khả dụng!');
        return;
    }

    if ($prize->quantity !== null && $prize->quantity <= 0) {
        $this->dispatch('notify', message: 'Phần thưởng đã hết!');
        return;
    }

    $prize->decrement('quantity');

    $wonPrize = WonPrize::create([
        'prize_id'   => $prize->id,
        'user_id'    => $user->id,
        'username'   => $user->name,
        'won_at'     => now(),
        'voucher_id' => $prize->voucher_id,
    ]);

    // 👇 Thêm bản ghi vào bảng voucher_used nếu có voucher
    if ($prize->voucher_id) {
        VoucherUsed::create([
            'voucher_id' => $prize->voucher_id,
            'user_id'    => $user->id,
            'is_used'    => false,
            'status'     => 'active',
        ]);
    }

    $this->updateSpinInfo();
    $this->loadHistory();

    $this->dispatch('notify', message: $prize->name);
    $this->dispatch('spinResult', prize: $prize->name);
}


    public function render()
    {
        return view('minigame::livewire.component.minigame', [
            'nextSpinTime' => $this->nextSpinTime,
        ]);
    }
}
