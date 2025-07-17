<?php

namespace Modules\UserModule\Livewire\Components;

use App\Models\UserPoint;
use App\Models\VoucherUsed;
use App\Models\Voucher;
use App\Models\Membership as MembershipModel;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Membership extends Component
{
    public function render()
    {
        return view('usermodule::livewire.components.membership');
    }
}
