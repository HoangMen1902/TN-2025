<?php

namespace Modules\UserModule\Livewire\Components;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Models\EmailChangeOtp;
use Illuminate\Support\Facades\Hash;

class UpdateInfomation extends Component
{
    use WithFileUploads;

    public $name, $email, $phone, $birthday, $avatar, $gender, $username;
    public $newAvatar;
    public $newEmail, $otp;
    public $otpSent = false;

    public $showEmailModal = false;

    public function mount()
    {
        $user = Auth::user();
        $this->username = $user->username;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->newEmail = $user->email;
        $this->phone = $user->phone;

        $this->birthday = $user->birthday
            ? Carbon::parse($user->birthday)->format('Y-m-d')
            : null;
        $this->avatar = $user->avatar;
        $this->gender = $user->gender;
    }

    public function rules()
    {
        $rules = [
            'name' => 'required|string|min:2|max:100',
            'username' => 'required|string|min:2|max:100',
            'newEmail' => 'required|email',
            'phone' => 'required|regex:/^[0-9]{10,11}$/',
            'birthday' => 'nullable|date',
            'gender' => 'nullable',
        ];

        if ($this->newAvatar) {
            $rules['newAvatar'] = 'nullable|image|max:1024';
        }

        if ($this->newEmail !== $this->email) {
            $rules['otp'] = 'required|digits:6';
        }

        return $rules;
    }


    public function updated($propertyName)
    {
        $this->validateOnly($propertyName, $this->rules());
    }

    // Mở modal thay đổi email
    public function openEmailModal()
    {
        $this->otp = null;
        $this->otpSent = false;
        $this->showEmailModal = true;
    }

    // Đóng modal
    public function closeEmailModal()
    {
        $this->showEmailModal = false;
    }

    public function sendOtp()
    {
        $this->validateOnly('newEmail');

        // Kiểm tra email đã tồn tại
        if (\App\Models\User::where('email', $this->newEmail)->exists()) {
            $this->addError('newEmail', 'Email đã được sử dụng bởi tài khoản khác.');
            return;
        }

        // Giới hạn gửi OTP 3 lần/ngày
        $otpCount = EmailChangeOtp::where('user_id', Auth::id())
            ->whereDate('created_at', Carbon::today())
            ->count();

        if ($otpCount >= 10) {
            $this->addError('newEmail', 'Bạn đã gửi quá nhiều OTP trong ngày hôm nay. Vui lòng thử lại sau.');
            return;
        }

        $otp = rand(100000, 999999);

        EmailChangeOtp::create([
            'user_id' => Auth::id(),
            'new_email' => $this->newEmail,
            'otp_code' => Hash::make($otp),
            'expires_at' => Carbon::now()->addMinutes(10),
        ]);

        Mail::send('emails.otp', ['otp' => $otp, 'name' => Auth::user()->name], function ($message) {
            $message->to($this->newEmail)
                ->subject('Xác thực thay đổi email');
        });

        $this->otpSent = true;
        session()->flash('success', 'Đã gửi mã OTP đến email mới.');
    }


    public function save()
    {
        $this->validate($this->rules());

        $user = Auth::user();

        // Xác thực OTP nếu email thay đổi
        if ($this->newEmail !== $this->email) {
            $record = EmailChangeOtp::where('user_id', $user->id)
                ->where('new_email', $this->newEmail)
                ->where('expires_at', '>', Carbon::now())
                ->latest()
                ->first();

            if (!$record || !Hash::check($this->otp, $record->otp_code)) {
                session()->flash('error', 'Mã OTP không đúng hoặc đã hết hạn.');
                return;
            }

            $user->email = $this->newEmail;
            $this->email = $this->newEmail;

            $record->delete(); // Xóa OTP sau khi sử dụng

            $this->closeEmailModal();
        }

        // Cập nhật avatar nếu có
        if ($this->newAvatar) {
            $path = $this->newAvatar->store('avatars', 'public');
            $this->avatar = $path;
            $user->avatar = $path;
        }

        $user->name = $this->name;
        $user->username = $this->username;
        $user->phone = $this->phone;
        $user->birthday = $this->birthday;
        $user->gender = $this->gender;
        $user->save();

        session()->flash('success', 'Cập nhật hồ sơ thành công.');
    }


    public function render()
    {
        return view('usermodule::livewire.components.update-infomation');
    }
}
