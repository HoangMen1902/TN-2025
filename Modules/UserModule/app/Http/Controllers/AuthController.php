<?php

namespace Modules\UserModule\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Support\Renderable;
use App\Models\Wishlist;
use App\Models\Cart;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('usermodule::auth.login');
    }
    public function login(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ], [
            'email.required' => 'Trường email là bắt buộc.',
            'email.email' => 'Email không hợp lệ.',
            'password.required' => 'Trường mật khẩu là bắt buộc.',
            'password.min' => 'Mật khẩu phải có ít nhất :min ký tự.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $oldSessionId = session()->getId();

        $credentials = $request->only('email', 'password');

        $currCart = Cart::where('session_id', '=',    $oldSessionId)->get();

        if (Auth::attempt($credentials)) {
            $userId = Auth::id();
            foreach ($currCart  as $cart) {
                $existingCart = Cart::where('user_id', $userId)
                    ->where('item_type', $cart->item_type)
                    ->where($cart->item_type === 'sku' ? 'sku_id' : 'combo_id', $cart->sku_id ?? $cart->combo_id)
                    ->first();

                if ($existingCart) {

                    $existingCart->quantity += $cart->quantity;
                    $existingCart->save();

                    $cart->delete();
                } else {
                    $cart->user_id = $userId;
                    $cart->save();
                }
            }
            return redirect()->intended('/')->with('success', 'Đăng nhập thành công!');
        }


        return redirect()->back()->withErrors([
            'email' => 'Email hoặc mật khẩu không đúng.',
        ])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/')->with('success', 'Đăng xuất thành công!');
    }
    public function showRegisterForm()
    {
        return view('usermodule::auth.register');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);
        return redirect('/')->with('success', 'Đăng ký thành công!');
    }
    //reser password
    public function handleResetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|confirmed|min:6',
        ], [
            'token.required' => 'Token không được để trống.',
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Email không hợp lệ.',
            'email.exists' => 'Email không tồn tại trong hệ thống.',
            'password.required' => 'Vui lòng nhập mật khẩu mới.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
            'password.min' => 'Mật khẩu phải có ít nhất :min ký tự.',
        ]);


        $record = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (!$record || now()->diffInMinutes($record->created_at) > 30) {
            return redirect()->route('forgot-password')->withErrors([
                'token' => 'Liên kết đã hết hạn hoặc không hợp lệ.',
            ]);
        }

        if (!$record) {
            return back()->withErrors(['token' => 'Token không hợp lệ hoặc đã hết hạn.']);
        }

        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('login')->with('success', 'Mật khẩu đã được cập nhật. Vui lòng đăng nhập lại.');
    }

    public function showForgotPasswordForm()
    {
        return view('usermodule::auth.forgot-password');
    }
    public function showChangeForgotPasswordForm(Request $request, $token)
    {
        return view('usermodule::auth.change-forgot-password', [
            'token' => $token,
        ]);
    }
    public function showChangePasswordForm()
    {
        return view('usermodule::auth.change-password');
    }
    public function showProfileInfomation()
    {
        return view('usermodule::profile.infomation');
    }

    public function showAddressInfomation()
    {
        return view('usermodule::profile.address');
    }

    public function showOrderInfomation()
    {
        return view('usermodule::profile.order');
    }
    public function showNotification()
    {
        return view('usermodule::profile.notification');
    }
    //          public function showWishList(): Renderable
    // {
    //     $wishLists = Wishlist::where('user_id', Auth::id())
    //         ->with('product')
    //         ->paginate(10);

    //     return view('usermodule::profile.wishlist', compact('wishLists'));
    // }
}
    //         public function wishListRemove(){
    //     return view('usermodule::profile.wishlist');
    // }
    //         public function voucherList(){
    //     return view('usermodule::profile.voucher');
    // }
