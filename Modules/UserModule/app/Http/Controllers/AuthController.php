<?php

namespace Modules\UserModule\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Support\Renderable;
use App\Models\Wishlist;
use App\Models\Cart;
use Illuminate\Support\Facades\DB;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Services\CartService;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('usermodule::auth.login');
    }
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:320',
            'password' => 'required',
        ], [
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Địa chỉ email không hợp lệ.',
            'email.max' => 'Email không được vượt quá 320 ký tự.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $credentials = $request->only('email', 'password');


        if (Auth::attempt($credentials)) {
            CartService::syncCartAfterLogin( Auth::id());
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
            'password_confirmation' => 'required|same:password',
        ], [
            'name.required' => 'Vui lòng nhập họ tên.',
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Email không hợp lệ.',
            'email.unique' => 'Email đã được sử dụng.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp.',
            'password_confirmation.required' => 'Vui lòng nhập lại mật khẩu.',
            'password_confirmation.same' => 'Mật khẩu xác nhận không khớp.',
        ]);


        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        $user->assignRole('user');
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
    public function showVerifyOTPForm()
    {
        return view('usermodule::auth.verify-otp');
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
        public function showMembership()
    {
        return view('usermodule::profile.membership');
    }
    //          public function showWishList(): Renderable
    // {
    //     $wishLists = Wishlist::where('user_id', Auth::id())
    //         ->with('product')
    //         ->paginate(10);

    //     return view('usermodule::profile.wishlist', compact('wishLists'));
    // }
    //         public function wishListRemove(){
    //     return view('usermodule::profile.wishlist');
    // }
    //         public function voucherList(){
    //     return view('usermodule::profile.voucher');
    // }


    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    // public function handleGoogleCallback()
    // {
    //     try {
    //         $googleUser = Socialite::driver('google')->user();
    //         $user = User::where('email', $googleUser->getEmail())->first();
    //         if (!$user) {
    //             $user = User::create([
    //                 'name'     => $googleUser->getName(),
    //                 'email'    => $googleUser->getEmail(),
    //                 'password' => bcrypt(Str::random(16)),
    //             ]);
    //         }
    //         Auth::login($user);
    //         return redirect('/')->with('success', 'Đăng nhập Google thành công!');
    //     } catch (\Exception $e) {
    //         return redirect('/dang-nhap')->with('error', 'Đăng nhập Google thát bại!');
    //     }
    // }
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Ghi log thông tin người dùng Google
            Log::info('Google login callback', [
                'google_id' => $googleUser->getId(),
                'name'      => $googleUser->getName(),
                'email'     => $googleUser->getEmail(),
            ]);

            $user = User::where('email', $googleUser->getEmail())->first();

            if (!$user) {
                $user = User::create([
                    'name'     => $googleUser->getName(),
                    'email'    => $googleUser->getEmail(),
                    'password' => bcrypt(Str::random(16)),
                ]);
                $user->assignRole('user');
                Log::info('Created new user from Google login', ['user_id' => $user->id]);
            }
            $oldSessionId = session()->getId();
            $currCart = Cart::where('session_id', '=',    $oldSessionId)->get();
            CartService::syncCartAfterLogin($currCart, $user->id);
            Auth::login($user);

            Log::info('User logged in via Google', ['user_id' => $user->id]);

            return redirect('/')->with('success', 'Đăng nhập Google thành công!');
        } catch (\Exception $e) {
            // Ghi log lỗi
            Log::error('Google login failed', ['error' => $e->getMessage()]);

            return redirect('/')->with('error', 'Đăng nhập Google thất bại. Vui lòng thử lại.');
        }
    }
    //doi mat khau
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'current_password.required' => 'Bạn chưa nhập mật khẩu hiện tại.',
            'password.required' => 'Bạn chưa nhập mật khẩu mới.',
            'password.string' => 'Mật khẩu mới phải là chuỗi ký tự.',
            'password.min' => 'Mật khẩu mới phải có ít nhất 8 ký tự.',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp.',
        ]);


        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()
                ->withErrors(['current_password' => 'Mật khẩu hiện tại không chính xác'])
                ->withInput();
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return redirect('/ho-so')->with('success', 'Đổi mật khẩu thành công!');
    }
}
