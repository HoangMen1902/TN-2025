<?php

namespace Modules\UserModule\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class Profile extends Component
{
    use WithFileUploads;

    public $name, $email, $phone, $birthday, $avatar, $gender;
    public $newAvatar;

    public function mount()
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone;
        $this->birthday = $user->birthday;
        $this->avatar = $user->avatar;
        $this->gender = $user->gender; 
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName, $this->rules());
    }

    public function rules()
    {
        return [
            'name' => 'required|string|min:2|max:100',
            'email' => 'required|email',
            'phone' => 'nullable|regex:/^[0-9]{10,11}$/',
            'birthday' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',  
            'newAvatar' => 'nullable|image|max:1024', 
        ];
    }

    public function save()
    {
        $this->validate($this->rules());

        $user = Auth::user();

        if ($this->newAvatar) {
            $path = $this->newAvatar->store('avatars', 'public');
            $this->avatar = $path;
            $user->avatar = $path;
        }

        $user->name = $this->name;
        $user->email = $this->email; 
        $user->phone = $this->phone; 
        $user->birthday = $this->birthday;
        $user->gender = $this->gender; // lưu gender
        $user->save();

        session()->flash('success', 'Cập nhật hồ sơ thành công.');
    }

    public function render()
    {
        return view('usermodule::livewire.profile');
    }
}

