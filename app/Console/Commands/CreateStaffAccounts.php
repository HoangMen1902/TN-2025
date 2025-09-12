<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateStaffAccounts extends Command
{
    protected $signature = 'make:staff-accounts';
    protected $description = 'Tạo tài khoản cho các staff mặc định';

    public function handle()
    {
        $staffs = [
            [
                'name' => 'Product Staff',
                'email' => 'productstaff@yourdomain.com',
                'role' => 'product staff',
            ],
            [
                'name' => 'Sales Staff',
                'email' => 'salesstaff@yourdomain.com',
                'role' => 'sales staff',
            ],
            [
                'name' => 'Marketing Staff',
                'email' => 'marketingstaff@yourdomain.com',
                'role' => 'marketing staff',
            ],
        ];

        $password = 'staff123'; 

        foreach ($staffs as $staff) {
            if (User::where('email', $staff['email'])->exists()) {
                $this->warn("Tài khoản {$staff['email']} đã tồn tại!");
                continue;
            }

            $user = User::create([
                'name' => $staff['name'],
                'email' => $staff['email'],
                'password' => Hash::make($password),
            ]);
            $user->assignRole($staff['role']);

            $this->info("Đã tạo tài khoản {$staff['name']} ({$staff['email']}) với role {$staff['role']}");
        }

        $this->info('Hoàn tất!');
    }
}