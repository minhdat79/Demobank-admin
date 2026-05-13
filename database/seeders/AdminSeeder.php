<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Tạo vai trò admin (nếu chưa có)
        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        // Tạo tài khoản admin (nếu chưa có)
        $user = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'), // <— mật khẩu: password
                'email_verified_at' => now(),
            ]
        );

        // Gán vai trò admin
        if (!$user->hasRole('admin')) {
            $user->assignRole($adminRole);
        }
    }
}
