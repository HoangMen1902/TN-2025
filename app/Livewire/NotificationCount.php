<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\UserNotification;

class NotificationCount extends Component
{
    public $count = 0;

    public function getCount()
    {
        if (Auth::check()) {
            return UserNotification::where('user_id', Auth::id())
                ->where('is_read', false)
                ->count();
        } else {
            return 0;
        }
    }

    public function render()
    {
        $this->count = $this->getCount();
        return view('livewire.notification-count');
    }
}