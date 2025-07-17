<?php

namespace Modules\UserModule\Livewire\Components;

use App\Models\UserPoint;
use App\Models\Membership as MembershipModel;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Membership extends Component
{
    public $currentMembershipName;
    public $currentPoints;
    public $nextMembershipName;
    public $nextMembershipPoints;
    public $pointsToNext;
    public $progressPercent;
    public $redeemable_points;

    public function mount()
    {
        $user = Auth::user();
        $this->currentPoints = $user->point->total_points ?? 0;
        $this->redeemable_points = $user->point->redeemable_points ?? 0;

        // Hạng hiện tại
        $currentMembership = $user->membership;
        $this->currentMembershipName = $currentMembership?->name ?? 'Chưa có';

        // Tìm hạng cao nhất mà user đủ điểm
        $eligibleMembership = MembershipModel::where('required_points', '<=', $this->currentPoints)
            ->where('status', 1)
            ->orderByDesc('required_points')
            ->first();

        // Nếu khác với hạng hiện tại thì cập nhật
        if ($eligibleMembership && $user->membership_id !== $eligibleMembership->id) {
            $user->membership_id = $eligibleMembership->id;
            $user->save();
        }

        // Gán lại sau khi cập nhật (phòng trường hợp thay đổi)
        $this->currentMembershipName = $eligibleMembership?->name ?? 'Chưa có';

        // Hạng tiếp theo
        $nextMembership = MembershipModel::where('required_points', '>', $this->currentPoints)
            ->where('status', 1)
            ->orderBy('required_points')
            ->first();

        $this->nextMembershipName = $nextMembership?->name ?? 'Cao nhất';
        $this->nextMembershipPoints = $nextMembership?->required_points ?? $this->currentPoints;

        // Tính điểm cần để lên hạng
        $this->pointsToNext = max(0, $this->nextMembershipPoints - $this->currentPoints);

        // Tính phần trăm tiến trình
        $previousTierPoints = $eligibleMembership?->required_points ?? 0;
        $range = $this->nextMembershipPoints - $previousTierPoints;
        $progress = $this->currentPoints - $previousTierPoints;
        $this->progressPercent = $range > 0 ? round(($progress / $range) * 100, 1) : 100;
    }


    public function render()
    {
        return view('usermodule::livewire.components.membership');
    }
}
