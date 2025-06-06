<?php
namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateSuperAdmin extends Command
{
    protected $signature = 'make:super-admin';
    protected $description = 'Tạo tài khoản super admin mặc định';

    public function handle()
    {
        $name = 'Super Admin';
        $email = 'admin@admin.com';
        $password = 'admin123';

        if (User::where('email', $email)->exists()) {
            $this->error('Email đã tồn tại!');
            return;
        }

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
        ]);
        $user->assignRole('Super_Admin');

        $this->info('Tạo tài khoản super admin thành công!');
        $this->info('Email: ' . $email);
        $this->info('Password: ' . $password);
    }
}