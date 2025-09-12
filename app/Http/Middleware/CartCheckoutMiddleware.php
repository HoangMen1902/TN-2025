<?php

namespace App\Http\Middleware;

use App\Models\Cart;
use Closure;
use Illuminate\Console\View\Components\Info;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CartCheckoutMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $userId = Auth::id();

        if (isset($userId)) {
            $validated = $request->validate([
                'cart_id' => 'required|array',
            ]);

            foreach ($request->cart_id as $cartId) {
                $cart = Cart::find($cartId);

                if (!$cart || $cart->user_id != $userId) {
                    return redirect('/gio-hang');
                }
            }
        } elseif (!isset($userId)) {
            return redirect('/dang-nhap')->with('error', 'Vui lòng đăng nhập để tiến hành đặt hàng');
        } else {
            return redirect('/gio-hang');
        }

        return $next($request);
    }
}
