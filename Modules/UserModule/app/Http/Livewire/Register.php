<?php

namespace Modules\UserModule\App\Http\Livewire;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class Register extends Component
{
    public $name, $email, $password, $password_confirmation;

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:6|confirmed',
    ];

    protected $messages = [
        'name.required' => 'Vui lòng nhập họ và tên.',
        'email.required' => 'Vui lòng nhập email.',
        'email.email' => 'Email không hợp lệ.',
        'email.unique' => 'Email này đã được sử dụng.',
        'password.required' => 'Vui lòng nhập mật khẩu.',
        'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
        'password.confirmed' => 'Mật khẩu xác nhận không khớp.',
    ];
    public function mount()
    {
        if (Auth::check()) {
            return redirect()->intended('/');
        }
    }

    public function register()
    {
        $this->validate();

        try {
            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
            ]);

            Auth::login($user);
            session()->flash('success', 'Đăng ký thành công!');

            return redirect()->intended('/');
        } catch (\Exception $e) {
            $this->addError('general', 'Đã xảy ra lỗi khi đăng ký. Vui lòng thử lại.');
            return null;
        }
    }

    public function render()
    {
        return view('usermodule::auth.register');
    }
}
