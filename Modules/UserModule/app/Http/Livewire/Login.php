<?php
namespace Modules\UserModule\App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class Login extends Component
{
    public $email, $password;

    protected $rules = [
        'email' => 'required|email',
        'password' => 'required|min:8',
    ];

 
    public function mount()
    {
        if (Auth::check()) {
            return redirect()->intended('/');
        }
    }

    public function login()
    {
        $this->validate();

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password])) {
            session(['user' => Auth::user()]);
            session()->flash('success', 'Đăng nhập thành công!');
            return redirect()->intended('/');
        }

        $user = User::where('email', $this->email)->first();

        if ($user) {
            if (!Hash::check($this->password, $user->password)) {
                $this->addError('password', 'Mật khẩu không đúng.');
            }
        } else {
            $this->addError('email', 'Email không tồn tại trong hệ thống.');
        }
    }

    public function render()
    {
        return view('usermodule::auth.login');
    }
}
