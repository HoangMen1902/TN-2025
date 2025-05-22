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
        $validated = $request->validate([
            'cart_id' => 'required|array',
        ]);


        $userId = Auth::id();
        $sessionId = session()->getId();

        if (isset($userId)) {

            foreach ($request->cart_id as $cartId) {
                $cart = Cart::find($cartId);
                
                if (!$cart || $cart->user_id != $userId) {
                    return redirect('/gio-hang');
                }
            }
        } elseif (!isset($userId) && isset($sessionId)) {
            Log::error('here 2');
            foreach ($request->cart_id as $cartId) {
                $cart = Cart::find($cartId);
                if (!$cart || $cart->session_id != $sessionId) {
                    return redirect('/gio-hang');
                }
            }
        } else {
            Log::error('here 3');
            return redirect('/gio-hang');
        }

        return $next($request);
    }
}
