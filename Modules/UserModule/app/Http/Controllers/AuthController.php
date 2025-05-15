<?php

namespace Modules\UserModule\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

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

       
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
         
            $request->session()->regenerate();
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
        return redirect('/login')->with('success', 'Đăng xuất thành công!');
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
    public function showForgotPasswordForm()
    {
        return view('usermodule::auth.forgot-password'); 
    }
    public function showChangeForgotPasswordForm()
    {
        return view('usermodule::auth.change-forgot-password'); 
    }
    public function showChangePasswordForm()
    {
        return view('usermodule::auth.change-password'); 
    }
    public function showProfileInfomation(){
        return view('usermodule::profile.infomation');
    }

    public function showAddressInfomation(){
        return view('usermodule::profile.address');
    }
    
       public function showOrderInfomation(){
        return view('usermodule::profile.order');
    }
           public function showNotification(){
        return view('usermodule::profile.notification');
    }
}
