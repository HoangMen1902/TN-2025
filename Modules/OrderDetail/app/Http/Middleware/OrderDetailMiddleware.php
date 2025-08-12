<?php

namespace Modules\OrderDetail\Http\Middleware;

use App\Models\PaymentDetail;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderDetailMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $userId = Auth::id();
        if(!$userId) {
            return redirect('/trang-chu')->with('error', 'Không hợp lệ');
        }
         $trackingId = $request->route('tracking_id');

        if (!$trackingId || !preg_match('/^[A-Z0-9]+$/', $trackingId)) {
            return redirect('/trang-chu')->with('error', 'Không hợp lệ');
        }

        $payment = PaymentDetail::where('tracking_id', $trackingId)->first();
        if(!$payment) {
            return redirect('/trang-chu')->with('error', 'Không hợp lệ');
        }

        if($payment->order->user_id !== $userId) {
            return redirect('/trang-chu')->with('error', 'Không hợp lệ');
        }


        return $next($request);
    }
}
