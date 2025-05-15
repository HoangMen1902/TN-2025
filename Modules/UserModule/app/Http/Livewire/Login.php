<?php
namespace Modules\UserModule\App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Login extends Component
{
    public $email, $password;

    public function login()
    {
        $this->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password])) {
            return redirect()->intended('/');
        }

        $this->addError('email', 'Thông tin đăng nhập không đúng.');
    }

    public function render()
    {
        return view('usermodule::auth.login');
    }
}