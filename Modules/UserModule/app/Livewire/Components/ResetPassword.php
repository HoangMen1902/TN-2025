<?php

namespace Modules\UserModule\Livewire\Components;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordMail;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class ResetPassword extends Component
{
    public $email;

    public function sendResetCode()
    {
        $this->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.exists' => 'Email không tồn tại trong hệ thống.',
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Email không hợp lệ.',
        ]);


        try {
            $token = Str::random(64);

            DB::table('password_reset_tokens')->updateOrInsert(
                ['email' => $this->email],
                ['token' => $token, 'created_at' => now()]
            );
            

            Mail::to($this->email)->send(new ResetPasswordMail($token, $this->email));

            // Thông báo thành công
            $this->dispatch('toast', type: 'success', message: 'Gửi mail khôi phục thành công');
        } catch (\Exception $e) {
            Log::error('Lỗi gửi mail khôi phục: ' . $e->getMessage());

            // Thông báo lỗi cho người dùng
            $this->dispatch('toast', type: 'error', message: 'Không thể gửi mail. Vui lòng thử lại sau.');
        }
    }

    public function render()
    {
        return view('usermodule::livewire.components.reset-password');
    }
}
