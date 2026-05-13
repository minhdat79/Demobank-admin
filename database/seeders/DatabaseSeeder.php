<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Gọi seeder AdminSeeder để tạo tài khoản admin
        $this->call(AdminSeeder::class);
    }
}
