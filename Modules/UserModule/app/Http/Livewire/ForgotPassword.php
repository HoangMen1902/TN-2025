<?php
namespace Modules\UserModule\App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Password;

class ForgotPassword extends Component
{
    public $email;

    public function sendResetLink()
    {
        $this->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $status = Password::sendResetLink(['email' => $this->email]);

        session()->flash('status', __($status));
    }

    public function render()
    {
        return view('usermodule::auth.forgot-password');
    }
}
