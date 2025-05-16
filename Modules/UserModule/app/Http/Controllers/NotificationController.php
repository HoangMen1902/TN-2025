<?php

namespace Modules\UserModule\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $types = Notification::select('notification_type')->distinct()->pluck('notification_type')->toArray();
        $tabs = ['all' => 'Tất cả'];
        foreach ($types as $type) {
            $tabs[$type] = $type;
        }

        $selectedTab = $request->get('tab', 'all');

        $query = Notification::query();
        if ($selectedTab !== 'all') {
            $query->where('notification_type', $selectedTab);
        }

        $notifications = $query->orderBy('id', 'desc')->paginate(10);

        return view('usermodule::profile.notification', compact('tabs', 'notifications', 'selectedTab'));
    }
}
